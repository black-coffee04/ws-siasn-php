<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;
use SiASN\Sdk\Exceptions\SiasnServiceException;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Utils\Mime;

/**
 * Class PnsService
 *
 * Layanan untuk mengakses data PNS.
 */
class PnsService implements ServiceInterface
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
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response = $httpClient->get("/apisiasn/1.0/{$endpoint}/{$args}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth' => 'bearer ' . $this->getSsoAccessToken(),
                'Accept' => 'application/json'
            ]
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Mengirim permintaan HTTP untuk refresh data ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param string $pnsId ID PNS yang akan di-refresh.
     * @return bool Status hasil permintaan refresh.
     */
    protected function refresh(string $endpoint, string $pnsId): bool
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response = $httpClient->get("/apisiasn/1.0/{$endpoint}", [
            'query' => [
                'pns_orang_id' => $pnsId
            ],
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth' => 'bearer ' . $this->getSsoAccessToken(),
                'Accept' => 'application/json'
            ]
        ]);

        return isset($response['Error']) && $response['Error'] === 'false';
    }

    /**
     * Mengambil data utama PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data utama PNS.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function dataOrangTua(string $nip): array
    {
        return $this->request('pns/data-ortu', $nip);
    }

    /**
     * Mengambil nilai IP ASN berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data nilai IP ASN.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function nilaiIpAsn(string $nip): array
    {
        return $this->request('pns/nilaiipasn', $nip . '?nipBaru=' . $nip);
    }

    /**
     * Menyegarkan data jabatan PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return bool Status hasil penyegaran.
     */
    public function refreshJabatan(string $nip): bool
    {
        $pns = $this->dataUtama($nip);
        return $this->refresh("pns/data-utama-jabatansync", $pns["id"]);
    }

    /**
     * Menyegarkan data golongan PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return bool Status hasil penyegaran.
     */
    public function refreshGolongan(string $nip): bool
    {
        $pns = $this->dataUtama($nip);
        return $this->refresh("pns/data-utama-golongansync", $pns["id"]);
    }

    /**
     * Mengambil foto PNS berdasarkan NIP dan menyimpannya ke file.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return $this
     */
    public function foto(string $nip)
    {
        $pns = $this->dataUtama($nip);
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $this->response = $httpClient->get("/apisiasn/1.0/pns/photo/{$pns['id']}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
                'Accept'        => 'application/json'
            ]
        ]);

        return $this;
    }

    /**
     * Menetapkan nama file untuk disimpan.
     *
     * @param string $fileName Nama file.
     * @return $this
     */
    public function setFileName(string $fileName)
    {
        $contentType = $this->response->getHeaderLine('Content-Type');
        $extension = (new Mime)->get($contentType);

        $this->fileName = $fileName . "." . $extension;
        return $this;
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
     * Menyimpan data foto ke dalam file.
     *
     * @return string Nama file yang disimpan.
     * @throws SiasnRequestException Jika foto tidak tersedia.
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
     * Mengirimkan data foto ke output stream untuk diunduh.
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
