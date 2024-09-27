<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Traits\ResponseTransformerTrait;

class KenaikanPangkatService implements ServiceInterface
{
    use ResponseTransformerTrait;

    /**
     * @var AuthenticationService Instance dari AuthenticationService untuk otentikasi.
     */
    private $authentication;

    /**
     * @var Config Instance dari Config yang menyimpan konfigurasi aplikasi.
     */
    private $config;

    /**
     * Constructor untuk KenaikanPangkatService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config = $config;
    }

    /**
     * Mengambil data kenaikan pangkat berdasarkan periode.
     *
     * @param string $periode Periode kenaikan pangkat (format: Y-m-d).
     * @return array Data kenaikan pangkat dari API.
     */
    public function get(string $periode): array
    {
        return $this->request('/apisiasn/1.0/pns/list-kp-instansi', ['periode' => date('Y-m-d', strtotime($periode))]);
    }

    /**
     * Mengunggah dokumen SK kenaikan pangkat.
     *
     * @param string $idUsulan ID usulan kenaikan pangkat.
     * @param string $nomorSk Nomor SK.
     * @param string $tanggalSk Tanggal SK.
     * @param mixed $file Path atau URL file yang akan diunggah.
     * @return array Response data dari API.
     * @throws SiasnDataException Jika terjadi kesalahan saat mengunggah dokumen.
     */
    public function uploadDokumenSk(string $idUsulan, string $nomorSk, string $tanggalSk, $file): array
    {
        $fileStream = $this->getFileStream($file);
        $httpClient = new HttpClient($this->config->getApiBaseUrl());

        $multipart = [
            ['name' => 'file', 'contents' => $fileStream, 'filename' => basename($file)],
            ['name' => 'id_usulan', 'contents' => $idUsulan],
            ['name' => 'no_sk', 'contents' => $nomorSk],
            ['name' => 'tgl_sk', 'contents' => $tanggalSk]
        ];

        $response = $httpClient->post('/apisiasn/1.0/upload-dok-sk-kp', [
            'multipart' => $multipart,
            'headers'   => $this->getHeaders()
        ]);

        return $response;
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
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        return $httpClient->head($url)->getStatusCode() === 200;
    }

    /**
     * Melakukan permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param array $query Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     */
    private function request(string $endpoint, array $query): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response = $httpClient->get($endpoint, [
            'query' => $query,
            'headers' => $this->getHeaders()
        ]);

        return $this->transformResponse($response);
    }

    /**
     * Mendapatkan header untuk permintaan HTTP.
     *
     * @return array Header untuk permintaan HTTP.
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
            'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json'
        ];
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
