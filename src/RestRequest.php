<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Exceptions\SiasnRequestException;
use InvalidArgumentException;

class RestRequest
{
    private $body;
    private $headers = [];

    /**
     * Melakukan permintaan HTTP GET.
     *
     * @param array $config Konfigurasi permintaan.
     * @return object Respon dari server, termasuk header dan body.
     * @throws SiasnRequestException Jika terjadi kesalahan saat melakukan permintaan.
     */
    public function get(array $config): object
    {
        $this->validateConfig($config);
        $this->executeRequest($config);
        return $this;
    }

    /**
     * Melakukan permintaan HTTP POST.
     *
     * @param  array $config Konfigurasi permintaan.
     * @param  array $data Data yang akan dikirim dalam permintaan.
     * @return object Respon dari server, termasuk header dan body.
     * @throws SiasnRequestException Jika terjadi kesalahan saat melakukan permintaan.
     */
    public function post(array $config, array $data = []): object
    {
        $this->validateConfig($config);
        $this->executeRequest($config, $data);
        return $this;
    }

    /**
     * Mendapatkan body yang telah di-parse dari respons HTTP.
     *
     * @return array Body respons yang telah di-parse.
     */
    public function getBody(): array
    {
        $decodedBody = json_decode($this->body, true);
        return $decodedBody ?? [];
    }
    
    /**
     * Mendapatkan body string dari respons HTTP.
     *
     * @return string Body respons yang telah di-parse.
     */
    public function getContent(): string
    {
        return $this->body;
    }

    /**
     * Mendapatkan nilai dari header tertentu dari respons.
     *
     * @param string $key Kunci header yang ingin diambil nilainya.
     * @return string Nilai dari header tersebut.
     */
    public function getHeader(string $key): string
    {
        return $this->headers[$key] ?? '';
    }

    /**
     * Mendapatkan semua header dari respons HTTP.
     *
     * @return array Header dari respons HTTP.
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Validasi konfigurasi permintaan.
     *
     * @param array $config Konfigurasi permintaan.
     * @throws InvalidArgumentException Jika URL tidak disediakan dalam konfigurasi.
     */
    private function validateConfig(array $config): void
    {
        if (empty($config['url'])) {
            throw new InvalidArgumentException('URL diperlukan dalam konfigurasi permintaan.');
        }
    }

    /**
     * Menjalankan permintaan cURL.
     *
     * @param array $config Konfigurasi permintaan.
     * @param array $data Data yang akan dikirim dalam permintaan (opsional).
     * @throws SiasnRequestException Jika terjadi kesalahan cURL atau kode status HTTP menunjukkan kesalahan.
     */
    private function executeRequest(array $config, array $data = []): void
    {
        $ch = curl_init();
        $options = $this->buildCurlOptions($config, $data);
        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        list($this->headers, $this->body) = $this->splitResponse($ch, $response);

        $this->handleCurlError($ch, $httpCode, $this->body);
        curl_close($ch);
    }

    /**
     * Memisahkan header dan body dari respons.
     *
     * @param resource $ch Handle cURL.
     * @param string $response Respons dari server.
     * @return array Array dengan elemen pertama sebagai header dan kedua sebagai body.
     */
    private function splitResponse($ch, string $response): array
    {
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers    = $this->parseHeaders(substr($response, 0, $headerSize));
        $body       = substr($response, $headerSize);
        
        return [$headers, $body];
    }

    /**
     * Parse headers HTTP mentah menjadi array asosiatif.
     *
     * @param string $rawHeaders String headers HTTP mentah.
     * @return array Array asosiatif dari headers yang telah diparse.
     */
    private function parseHeaders(string $rawHeaders): array
    {
        $headers = [];
        $key = '';

        foreach (explode("\n", $rawHeaders) as $headerLine) {
            $headerLine = trim($headerLine);

            if (empty($headerLine)) {
                continue;
            }

            $headerParts = explode(':', $headerLine, 2);

            if (isset($headerParts[1])) {
                $headerName = trim($headerParts[0]);
                $headerValue = trim($headerParts[1]);

                if (!isset($headers[$headerName])) {
                    $headers[$headerName] = $headerValue;
                } elseif (is_array($headers[$headerName])) {
                    $headers[$headerName] = array_merge($headers[$headerName], [trim($headerValue)]);
                } else {
                    $headers[$headerName] = array_merge([$headers[$headerName]], [trim($headerValue)]);
                }

                $key = $headerName;
            } elseif (!empty($headerParts[0])) {
                if (substr($headerParts[0], 0, 1) == "\t" && !empty($key)) {
                    $headers[$key] .= "\r\n\t" . trim($headerParts[0]);
                } elseif (empty($key)) {
                    $headers[0] = trim($headerParts[0]);
                }
            }
        }

        return $headers;
    }

    /**
     * Menangani kesalahan cURL.
     *
     * @param resource $ch Handle cURL.
     * @param int $httpCode Kode status HTTP dari respons.
     * @param string $response Respon dari server.
     * @return void.
     * @throws SiasnRequestException Jika terjadi kesalahan cURL atau kode status HTTP menunjukkan kesalahan selain HTTP 404.
     */
    private function handleCurlError($ch, int $httpCode, string $response): void
    {
        if (curl_errno($ch)) {
            throw new SiasnRequestException('Kesalahan Curl: ' . curl_error($ch), 400);
        }
    }

    /**
     * Membangun opsi cURL berdasarkan konfigurasi.
     *
     * @param array $config Konfigurasi permintaan.
     * @param array $data Data yang akan dikirim dalam permintaan (opsional).
     * @return array Opsi cURL yang telah dibangun.
     */
    private function buildCurlOptions(array $config, array $data = []): array
    {
        $options = [
            CURLOPT_URL            => $config['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_HTTPHEADER     => $config['headers'] ?? [],
        ];

        if (isset($config['username']) && isset($config['password'])) {
            $options[CURLOPT_USERPWD] = $config['username'] . ':' . $config['password'];
        }

        if (!empty($data)) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $this->formatPostFields($data, $config['contentType'] ?? 'json');
        }

        return $options;
    }

    /**
     * Memformat data POST sesuai dengan jenis konten.
     *
     * @param array $data Data yang akan diformat.
     * @param string $contentType Jenis konten data.
     * @return mixed Data yang telah diformat.
     */
    private function formatPostFields(array $data, string $contentType)
    {
        switch ($contentType) {
            case 'urlencoded':
                return http_build_query($data);
            case 'form-data':
                return $data;
            case 'json':
            default:
                return json_encode($data);
        }
    }
}
