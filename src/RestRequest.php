<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Exceptions\RestRequestException;
use InvalidArgumentException;

class RestRequest
{
    /**
     * Melakukan permintaan HTTP GET.
     *
     * @param array $config Konfigurasi permintaan.
     * @return string Respon dari server.
     * @throws RestRequestException Jika terjadi kesalahan saat melakukan permintaan.
     */
    public function get(array $config): string
    {
        $this->validateConfig($config);

        $ch = curl_init();
        $options = $this->buildCurlOptions($config);

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->handleCurlError($ch, $httpCode, $response);
        curl_close($ch);

        return $response;
    }

    /**
     * Melakukan permintaan HTTP POST.
     *
     * @param array $config Konfigurasi permintaan.
     * @param array $data Data yang akan dikirim dalam permintaan.
     * @return string Respon dari server.
     * @throws RestRequestException Jika terjadi kesalahan saat melakukan permintaan.
     */
    public function post(array $config, array $data = []): string
    {
        $this->validateConfig($config);

        $ch = curl_init();
        $options = $this->buildCurlOptions($config, $data);

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->handleCurlError($ch, $httpCode, $response);
        curl_close($ch);

        return $response;
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
            throw new InvalidArgumentException('URL is required in the request configuration.');
        }
    }

    /**
     * Menangani kesalahan cURL.
     *
     * @param resource $ch Resource cURL.
     * @param int $httpCode Kode status HTTP dari respons.
     * @param string $response respon dari service siasn.
     * @throws RestRequestException Jika terjadi kesalahan cURL atau kode status HTTP menunjukkan kesalahan.
     */
    private function handleCurlError($ch, int $httpCode, string $response): void
    {
        if (curl_errno($ch)) {
            throw new RestRequestException('Curl error: ' . curl_error($ch), 400);
        }

        if ($httpCode >= 400) {
            $errorMessage = 'HTTP error: ' . $httpCode;
            
            if (!empty($response) && ($decodedResponse = json_decode($response, true)) !== null) {
                $errorDescription = $decodedResponse['error_description'] ?? '';
                $errorMessage .= ', Error: ' . $errorDescription;
            } else {
                $errorMessage .= ', Response: ' . $response;
            }
            
            throw new RestRequestException($errorMessage, $httpCode);
        }
    }

    /**
     * Membangun opsi cURL berdasarkan konfigurasi.
     *
     * @param array $config Konfigurasi permintaan.
     * @param array $data Data yang akan dikirim dalam permintaan (opsional untuk metode POST).
     * @return array Opsi cURL yang telah dibangun.
     */
    private function buildCurlOptions(array $config, array $data = []): array
    {
        $options = [
            CURLOPT_URL            => $config['url'],
            CURLOPT_RETURNTRANSFER => true,
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
