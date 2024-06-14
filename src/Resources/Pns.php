<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;
use SiASN\Sdk\Mime;

/**
 * Class Pns
 *
 * Kelas ini digunakan untuk mengakses data PNS dari layanan SiASN.
 */
class Pns extends Authentication
{
    protected $response;
    protected $filePath;
    protected $fileName;
    protected $headers;

    /**
     * Membuat instance PNS.
     *
     * @param Config $config Objek konfigurasi.
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }

    /**
     * Memvalidasi NIP yang diberikan.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @throws SiasnRequestException Jika NIP kosong.
     */
    private function validateNip(string $nip): void
    {
        if (empty($nip)) {
            throw new SiasnRequestException('Nomor Induk Pegawai (NIP) harus diisi', 422);
        }
    }

    /**
     * Mengambil data dari API berdasarkan endpoint dan NIP yang diberikan.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data yang diambil dari API.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    protected function fetchData(string $endpoint, string $nip): array
    {
        $this->validateNip($nip);

        $requestOptions = [
            'url'     => $this->config->getApiBaseUrl() . $endpoint . $nip,
            'headers' => [
                'Accept: application/json',
                'Auth: bearer ' . $this->getSsoAccessToken(),
                'Authorization: Bearer ' . $this->getWsoAccessToken(),
            ]
        ];

        $response = $this->get($requestOptions)->getBody();

        if (!isset($response['data'])) {
            throw new SiasnRequestException('Gagal mengambil data dari API.', 500);
        }

        return is_array($response['data']) ? $response['data'] : [];
    }

    /**
     * Mengambil data utama PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data utama PNS.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getDataUtama(string $nip): array
    {
        return $this->fetchData('/pns/data-utama/', $nip);
    }

    /**
     * Mengambil data pasangan PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data pasangan PNS.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getDataPasangan(string $nip): array
    {
        return $this->fetchData('/pns/data-pasangan/', $nip);
    }

    /**
     * Mengambil data anak PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data anak PNS.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getDataAnak(string $nip): array
    {
        return $this->fetchData('/pns/data-anak/', $nip);
    }

    /**
     * Mengambil data orang tua PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data orang tua PNS.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getDataOrangTua(string $nip): array
    {
        return $this->fetchData('/pns/data-ortu/', $nip);
    }

    /**
     * Memperbarui data jabatan PNS berdasarkan NIP.
     *
     * @param string $pnsOrangId Nomor Induk Pegawai.
     * @return bool True jika pembaruan berhasil, false jika gagal.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function refreshJabatan(string $pnsOrangId): bool
    {
        return $this->refreshData('/pns/data-utama-jabatansync?pns_orang_id=', $pnsOrangId);
    }

    /**
     * Memperbarui data golongan PNS berdasarkan NIP.
     *
     * @param string $pnsOrangId Nomor Induk Pegawai.
     * @return bool True jika pembaruan berhasil, false jika gagal.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function refreshGolongan(string $pnsOrangId): bool
    {
        return $this->refreshData('/pns/data-utama-golongansync?pns_orang_id=', $pnsOrangId);
    }

    /**
     * Mengambil nilai IP ASN berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data nilai IP ASN.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getNilaiIpAsn(string $nip): array
    {
        return $this->fetchData('/pns/nilaiipasn/', $nip . '?nipBaru='.$nip);
    }

    /**
     * Mengambil foto PNS berdasarkan ID orang.
     *
     * @param string $pnsOrangId ID Orang PNS.
     * @return $this
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getFoto(string $pnsOrangId)
    {
        $requestOptions = [
            'url'     => $this->config->getApiBaseUrl() . '/pns/photo/' . $pnsOrangId,
            'headers' => [
                'Accept: application/json',
                'Auth: bearer ' . $this->getSsoAccessToken(),
                'Authorization: Bearer ' . $this->getWsoAccessToken(),
            ]
        ];

        $response       = $this->get($requestOptions);
        $decodeResponse = json_decode($response->getContent(), true);

        if ($decodeResponse !== null && isset($decodeResponse['error'])) {
            throw new SiasnRequestException('Gagal mendapatkan foto: ' . $decodeResponse['message']);
        }

        $this->response = $response;
        return $this;
    }

    /**
     * Menyimpan data foto ke lokasi tertentu.
     *
     * @param string $path Path lokasi penyimpanan.
     * @return $this
     */
    public function saveTo(string $path)
    {
        $this->filePath = $path;
        return $this;
    }

    /**
     * Membuat direktori jika belum ada.
     *
     * @param string $path Path dari direktori.
     * @return void
     * @throws DataFetchException Jika terjadi kesalahan saat membuat direktori.
     */
    private function ensureDirectoryExists(string $path)
    {
        if (!is_dir($path)) {
            if (!mkdir($path, 0777, true) && !is_dir($path)) {
                throw new SiasnRequestException('Gagal membuat direktori: ' . $path);
            }
        }
    }

    /**
     * Menyimpan data foto dengan nama file tertentu.
     *
     * @param string $filename Nama file untuk menyimpan data foto.
     * @return string
     */
    public function withFileName(string $filename): string
    {
        $mime = $this->response->getHeader('content-type');
        $extension = (new Mime())->get($mime);
        $this->fileName = $filename . '.' . $extension;
        return $this->saveFotoToFile();
    }

    /**
     * Menyimpan data foto ke dalam file.
     *
     * @return string
     */
    private function saveFotoToFile(): string
    {
        if ($this->response->getContent() === null) {
            throw new SiasnRequestException('Foto tidak tersedia.');
        }

        $directory = rtrim($this->filePath, DIRECTORY_SEPARATOR);
        $this->ensureDirectoryExists($directory);

        $fullPath = rtrim($this->filePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $this->fileName;
        file_put_contents($fullPath, $this->response->getContent());
        return $this->fileName;
    }

    /**
     * Mengubah data utama PNS.
     *
     * Metode ini mengirim permintaan untuk memperbarui data utama PNS dengan data yang diberikan.
     *
     * @param array $data Data baru untuk memperbarui data utama PNS.
     * @return array Respon dari API setelah mengubah data.
     * @throws SiasnRequestException Jika terjadi kesalahan saat mengubah data utama.
     */
    public function updateDataUtama(array $data): array
    {
        $requestOptions = [
            'url'         => $this->config->getApiBaseUrl() . '/pns/data-utama-update',
            'headers' => [
                'Content-Type: application/json',
                'Accept: application/json',
                'Auth: bearer ' . $this->getSsoAccessToken(),
                'Authorization: Bearer ' . $this->getWsoAccessToken(),
            ],
            'contentType' => 'json'
        ];

        $response = $this->post($requestOptions, $data)->getBody();
        if (isset($response['code']) && $response['code']) {
            return $response;
        }

        throw new SiasnRequestException('Gagal mengubah data utama: ' . $response['message']);
    }


    /**
     * Memperbarui data PNS berdasarkan NIP.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param string $pnsOrangId
     *      * @return bool True jika pembaruan berhasil, false jika gagal.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    protected function refreshData(string $endpoint, string $pnsOrangId): bool
    {
        $this->validateNip($pnsOrangId);

        $requestOptions = [
            'url'     => $this->config->getApiBaseUrl() . $endpoint . $pnsOrangId,
            'headers' => [
                'Accept: application/json',
                'Auth: bearer ' . $this->getSsoAccessToken(),
                'Authorization: Bearer ' . $this->getWsoAccessToken(),
            ]
        ];

        $response = $this->get($requestOptions)->getBody();

        if (isset($response['Error']) && $response['Error']) {
            return true;
        }
        
        throw new SiasnRequestException('Gagal merefresh data: '.$response['Message'], 500);
    }
}
