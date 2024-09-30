<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;
use SiASN\Sdk\Exceptions\SiasnServiceException;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Traits\ResponseTransformerTrait;
use SiASN\Sdk\Utils\Mime;

/**
 * Class PnsService
 *
 * Layanan untuk mengakses data PNS.
 */
class PnsService implements ServiceInterface
{
    use ResponseTransformerTrait;

    private AuthenticationService $authentication;
    private Config $config;
    private object $response;
    private string $fileName;
    private string $filePath;

    /**
     * Constructor untuk PnsService.
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
     * Mengirim permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param string $args Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    protected function request(string $endpoint, string $args): array
    {
        return $this->sendRequest("apisiasn/1.0/{$endpoint}/{$args}");
    }

    /**
     * Mengirim permintaan HTTP untuk refresh data ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param string $pnsId ID PNS yang akan di-refresh.
     * @return array Status hasil permintaan refresh.
     */
    protected function refresh(string $endpoint, string $pnsId): array
    {
        return $this->sendRequest("apisiasn/1.0/{$endpoint}", [
            'query' => ['pns_orang_id' => $pnsId]
        ]);
    }

    /**
     * Mengirim permintaan HTTP secara umum.
     *
     * @param string $uri URI lengkap untuk permintaan.
     * @param array|null $options Opsi tambahan untuk permintaan.
     * @return array Data respon dari API.
     */
    private function sendRequest(string $uri, array $options = []): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $options['headers'] = [
            'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
            'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
            'Accept'        => 'application/json'
        ];

        $response = $httpClient->get("/{$uri}", $options);
        return $this->transformResponse($response);
    }

    /**
     * Memperbarui data utama PNS melalui API.
     *
     * @param array $data Data yang akan diperbarui. Harus mencakup semua field yang diperlukan oleh API.
     * @return array Hasil dari permintaan API, yang sudah ditransformasi.
     */
    public function updateDataUtama(array $data): array
    {
        $httpClient         = new HttpClient($this->config->getApiBaseUrl());
        $options['json']    = $data;
        $options['headers'] = [
            'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
            'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
            'Accept'        => 'application/json'
        ];

        $response = $httpClient->post("apisiasn/1.0/pns/data-utama-update", $options);
        return $this->transformResponse($response);
    }

    /**
     * Mengambil data utama PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data utama PNS.
     */
    public function dataUtama(string $nip): array
    {
        return $this->request('pns/data-utama', $nip);
    }

    /**
     * Mengambil data pasangan PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data pasangan PNS.
     */
    public function dataPasangan(string $nip): array
    {
        return $this->request('pns/data-pasangan', $nip);
    }

    /**
     * Mengambil data anak PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data anak PNS.
     */
    public function dataAnak(string $nip): array
    {
        return $this->request('pns/data-anak', $nip);
    }

    /**
     * Mengambil data orang tua PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data orang tua PNS.
     */
    public function dataOrangTua(string $nip): array
    {
        return $this->request('pns/data-ortu', $nip);
    }

    /**
     * Mengambil nilai IP ASN PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Nilai IP ASN PNS.
     */
    public function nilaiIpAsn(string $nip): array
    {
        return $this->request('pns/nilaiipasn', $nip . '?nipBaru=' . $nip);
    }

    /**
     * Melakukan refresh jabatan PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Status hasil refresh jabatan.
     */
    public function refreshJabatan(string $nip): array
    {
        return $this->refreshWithDataUtama("pns/data-utama-jabatansync", $nip);
    }

    /**
     * Melakukan refresh golongan PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Status hasil refresh golongan.
     */
    public function refreshGolongan(string $nip): array
    {
        return $this->refreshWithDataUtama("pns/data-utama-golongansync", $nip);
    }

    /**
     * Refresh data dengan menggunakan data utama PNS.
     *
     * @param string $endpoint Endpoint untuk refresh.
     * @param string $nip Nomor Induk Pegawai.
     * @return array Status hasil refresh.
     */
    private function refreshWithDataUtama(string $endpoint, string $nip): array
    {
        $pns = $this->dataUtama($nip);
        return $this->refresh($endpoint, $pns["data"]["id"]);
    }

    /**
     * Mengambil foto PNS berdasarkan NIP dan menyimpannya ke file.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return $this Instance dari PnsService.
     */
    public function foto(string $nip)
    {
        $pns = $this->dataUtama($nip);
        $this->response = $this->fetchFoto($pns['id']);
        return $this;
    }

    /**
     * Mengambil foto PNS dari API.
     *
     * @param string $pnsId ID PNS.
     * @return object Respon dari API.
     */
    private function fetchFoto(string $pnsId): object
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        return $httpClient->get("/apisiasn/1.0/pns/photo/{$pnsId}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
                'Accept'        => 'application/json'
            ]
        ]);
    }

    /**
     * Menetapkan nama file untuk disimpan.
     *
     * @param string $fileName Nama file.
     * @return $this Instance dari PnsService.
     */
    public function setName(string $fileName)
    {
        $this->fileName = $this->generateFileName($fileName);
        return $this;
    }

    /**
     * Menghasilkan nama file berdasarkan Content-Type.
     *
     * @param string $fileName Nama file.
     * @return string Nama file yang dihasilkan.
     */
    private function generateFileName(string $fileName): string
    {
        $contentType = $this->response->getHeaderLine('Content-Type');
        $extension = (new Mime)->get($contentType);
        return $fileName . '.' . $extension;
    }

    /**
     * Menyimpan file foto ke path yang ditentukan.
     *
     * @param string $path Path tempat menyimpan file.
     * @return string Nama file yang disimpan.
     * @throws SiasnServiceException Jika gagal menyimpan file.
     */
    public function saveTo(string $path): string
    {
        $this->filePath = rtrim($path, DIRECTORY_SEPARATOR);
        return $this->saveToFile();
    }

    /**
     * Menyimpan data foto ke dalam file.
     *
     * @return string Nama file yang disimpan.
     * @throws SiasnRequestException Jika foto tidak tersedia.
     */
    private function saveToFile(): string
    {
        $this->ensureDirectoryExists($this->filePath);
        $fullPath     = $this->filePath . DIRECTORY_SEPARATOR . $this->fileName;
        $fileContents = $this->response->getBody()->getContents();
        file_put_contents($fullPath, $fileContents);
        return $this->fileName;
    }

    /**
     * Memastikan direktori untuk menyimpan file sudah ada.
     *
     * @param string $path Path dari direktori.
     * @return void
     * @throws SiasnServiceException Jika direktori tidak dapat dibuat.
     */
    private function ensureDirectoryExists(string $path): void
    {
        if (!is_dir($path) && !mkdir($path, 0755, true) && !is_dir($path)) {
            throw new SiasnServiceException("Gagal membuat direktori: {$path}");
        }
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
