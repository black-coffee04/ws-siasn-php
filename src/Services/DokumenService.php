<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Exceptions\SiasnServiceException;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Utils\Mime;

class DokumenService implements ServiceInterface
{
    /**
     * @var AuthenticationService Instance dari AuthenticationService untuk otentikasi.
     */
    private $authentication;

    /**
     * @var Config Instance dari Config yang menyimpan konfigurasi aplikasi.
     */
    private $config;

    /**
     * @var HttpClient Instance dari HttpClient untuk koneksi HTTP.
     */
    private $httpClient;

    /**
     * @var object Response dari permintaan terakhir.
     */
    private $response;

    /**
     * @var string Nama file untuk disimpan.
     */
    private $fileName;

    /**
     * @var string Path tempat menyimpan file.
     */
    private $filePath;

    /**
     * Constructor untuk DokumenService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config         = $config;
        $this->httpClient     = new HttpClient($this->config->getApiBaseUrl());
    }

    /**
     * Mendownload dokumen dari API.
     *
     * @param mixed $args Argument untuk URI dokumen (string, array, atau objek).
     * @return $this
     * @throws SiasnServiceException Jika format argument tidak didukung.
     */
    public function download($args)
    {
        $dokUri = $this->resolveDokUri($args);

        $this->response = $this->httpClient->get('/apisiasn/1.0/download-dok?filePath=' . $dokUri, [
            'headers' => $this->getHeaders(),
        ]);

        return $this;
    }

    /**
     * Mendapatkan headers untuk permintaan HTTP.
     *
     * @return array Headers untuk permintaan HTTP.
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
            'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
            'Accept'        => 'application/json',
        ];
    }

    /**
     * Memecahkan URI dokumen berdasarkan jenis argument yang diberikan.
     *
     * @param mixed $args Argument untuk URI dokumen.
     * @return string URI dokumen yang dipecah.
     * @throws SiasnServiceException Jika format argument tidak didukung.
     */
    private function resolveDokUri($args): string
    {
        if (is_string($args)) {

            $decoded = json_decode($args, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && isset($decoded['dok_uri'])) {
                return $decoded['dok_uri'];
            }

            return $args;
        }

        if (is_array($args) && isset($args['dok_uri'])) {
            return $args['dok_uri'];
        }

        if (is_object($args) && property_exists($args, 'dok_uri')) {
            return $args->dok_uri;
        }

        throw new SiasnServiceException("Format argument tidak didukung");
    }

    /**
     * Menetapkan nama file untuk disimpan.
     *
     * @param string $fileName Nama file.
     * @return $this
     */
    public function setName(string $fileName)
    {
        $contentType = $this->response->getHeaderLine('Content-Type');
        $extension = (new Mime)->get($contentType);

        $this->fileName = $fileName . "." . $extension;
        return $this;
    }

    /**
     * Menyimpan file ke path yang ditentukan.
     *
     * @param string $path Path tempat menyimpan file.
     * @return string Nama file yang disimpan.
     * @throws SiasnServiceException Jika gagal menyimpan file.
     */
    public function saveTo(string $path): string
    {
        $this->filePath = $path;
        return $this->saveToFile();
    }

    /**
     * Memastikan direktori untuk menyimpan file sudah ada.
     *
     * @param string $path Path dari direktori.
     * @return void
     * @throws SiasnServiceException Jika gagal membuat direktori.
     */
    private function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new SiasnServiceException('Gagal membuat direktori: ' . $path);
            }
        }
    }

    /**
     * Menyimpan data dari response ke dalam file.
     *
     * @return string Nama file yang disimpan.
     * @throws SiasnServiceException Jika gagal menyimpan file.
     */
    private function saveToFile(): string
    {
        $directory = rtrim($this->filePath, DIRECTORY_SEPARATOR);
        $this->ensureDirectoryExists($directory);

        $fullPath = $directory . DIRECTORY_SEPARATOR . $this->fileName;
        $file     = $this->response->getBody()->getContents();
        file_put_contents($fullPath, $file);

        return $this->fileName;
    }

    /**
     * Mengirimkan data file ke output stream untuk diunduh.
     *
     * @return void
     */
    public function outputStream(): void
    {
        $content  = $this->response->getBody()->getContents();
        $fileSize = strlen($content);
        $mime     = $this->response->getHeaderLine('Content-Type');

        header('Content-Description: File Transfer');
        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . basename($this->fileName) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $fileSize);

        ob_clean();
        flush();

        echo $content;
        exit;
    }

    /**
     * Mengunggah dokumen dengan menyertakan ID referensi dokumen.
     *
     * @param string $idRefDokumen ID referensi dokumen.
     * @param string $file Lokasi file yang akan diunggah.
     * @return array Data yang dikembalikan dari respons API.
     * @throws SiasnServiceException Jika gagal melakukan pengunggahan.
     */
    public function upload(string $idRefDokumen, $file)
    {
        $fileStream = $this->getFileStream($file);
        $multipart  = [
            [
                'name'     => 'file',
                'contents' => $fileStream,
                'filename' => basename($file)
            ],
            [
                'name'     => 'id_ref_dokumen',
                'contents' => $idRefDokumen
            ]
        ];

        $response = $this->httpClient->post('/apisiasn/1.0/upload-dok', [
            'multipart' => $multipart,
            'headers'   => $this->getHeaders()
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Mendapatkan file stream dari path atau URL.
     *
     * @param mixed $file Path ke file atau URL dari dokumen.
     * @return resource File stream.
     * @throws SiasnDataException Jika file tidak ada atau tidak valid.
     */
    private function getFileStream($file)
    {
        if (empty($file)) {
            throw new SiasnDataException('File tidak boleh kosong.');
        }

        if (filter_var($file, FILTER_VALIDATE_URL)) {
            return $this->openUrlStream($file);
        }

        return $this->openFileStream($file);
    }

    /**
     * Membuka stream dari URL.
     *
     * @param string $url URL dari file.
     * @return resource File stream.
     * @throws SiasnDataException Jika URL tidak dapat diakses atau stream gagal dibuka.
     */
    private function openUrlStream(string $url)
    {
        if (!$this->isUrlAccessible($url)) {
            throw new SiasnDataException('URL tidak dapat diakses.');
        }

        $fileStream = fopen($url, 'r');
        if (!$fileStream) {
            throw new SiasnDataException('Gagal membuka URL.');
        }

        return $fileStream;
    }

    /**
     * Membuka stream dari path file.
     *
     * @param string $filePath Path ke file.
     * @return resource File stream.
     * @throws SiasnDataException Jika file tidak ditemukan atau stream gagal dibuka.
     */
    private function openFileStream(string $filePath)
    {
        if (!file_exists($filePath)) {
            throw new SiasnDataException('File tidak ditemukan.');
        }

        $fileStream = fopen($filePath, 'r');
        if (!$fileStream) {
            throw new SiasnDataException('Gagal membuka file.');
        }

        return $fileStream;
    }

    /**
     * Memeriksa apakah URL dapat diakses.
     *
     * @param string $url URL yang akan diperiksa.
     * @return bool True jika URL dapat diakses, false jika tidak.
     */
    private function isUrlAccessible(string $url): bool
    {
        $response = $this->httpClient->head($url);
        return $response->getStatusCode() === 200;
    }

    /**
     * Mengunggah riwayat dokumen dengan menyertakan ID riwayat dan ID referensi dokumen.
     *
     * @param string $idRiwayat ID riwayat dokumen.
     * @param string $idRefDokumen ID referensi dokumen.
     * @param string $file Lokasi file yang akan diunggah.
     * @return array Data yang dikembalikan dari respons API.
     * @throws SiasnServiceException Jika gagal melakukan pengunggahan.
     */
    public function uploadRiwayat(string $idRiwayat, string $idRefDokumen, $file)
    {
        $fileStream = $this->getFileStream($file);

        $multipart = [
            [
                'name'     => 'file',
                'contents' => $fileStream,
                'filename' => basename($file)
            ],
            [
                'name'     => 'id_riwayat',
                'contents' => $idRiwayat
            ],
            [
                'name'     => 'id_ref_dokumen',
                'contents' => $idRefDokumen
            ]
        ];

        $response = $this->httpClient->post('/apisiasn/1.0/upload-dok-rw', [
            'multipart' => $multipart,
            'headers'   => $this->getHeaders()
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Mendapatkan access token dari layanan SSO.
     *
     * @return string Access token SSO.
     */
    public function getSsoAccessToken(): string
    {
        return $this->authentication->getSsoAccessToken();
    }

    /**
     * Mendapatkan access token dari layanan WSO.
     *
     * @return string Access token WSO.
     */
    public function getWsoAccessToken(): string
    {
        return $this->authentication->getWsoAccessToken();
    }
}
