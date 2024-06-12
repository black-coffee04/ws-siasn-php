<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\RestRequestException;

/**
 * Class Pns
 *
 * Kelas ini digunakan untuk mengakses data PNS dari layanan SiASN.
 */
class Pns extends Authentication
{
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
     * @throws RestRequestException Jika NIP kosong.
     */
    private function validateNip(string $nip): void
    {
        if (empty($nip)) {
            throw new RestRequestException('Nomor Induk Pegawai (NIP) harus diisi', 422);
        }
    }

    /**
     * Mengambil data dari API berdasarkan endpoint dan NIP yang diberikan.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data yang diambil dari API.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
     */
    private function fetchData(string $endpoint, string $nip): array
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

        $response = $this->get($requestOptions);
        $decodedResponse = json_decode($response, true);

        if (!isset($decodedResponse['data'])) {
            throw new RestRequestException('Gagal mengambil data dari API.', 500);
        }

        return is_array($decodedResponse['data']) ? $decodedResponse['data'] : [];
    }

    /**
     * Mengambil data utama PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data utama PNS.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function getNilaiIpAsn(string $nip): array
    {
        return $this->fetchData('/pns/nilaiipasn/', $nip . '?nipBaru='.$nip);
    }

    /**
     * Mengambil foto PNS berdasarkan ID orang.
     *
     * @param string $pnsOrangId ID Orang PNS.
     * @return mixed Data foto PNS dalam bentuk string atau null jika tidak ada.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
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

        $response = $this->get($requestOptions);
        $decodedResponse = json_decode($response, true);

        if ($decodedResponse !== null && $decodedResponse['error']) {
            throw new RestRequestException('Gagal mendapatkan foto: '.$decodedResponse['message']);
        }

        return $response;
    }

    /**
     * Memperbarui data PNS berdasarkan NIP.
     *
     * @param string $endpoint Endpoint API yang akan diakses.
     * @param string $pnsOrangId
     *      * @return bool True jika pembaruan berhasil, false jika gagal.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
     */
    private function refreshData(string $endpoint, string $pnsOrangId): bool
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

        $response = $this->get($requestOptions);
        $decodedResponse = json_decode($response, true);

        if (isset($decodedResponse['Error']) && $decodedResponse['Error']) {
            return true;
        }

        throw new RestRequestException('Gagal merefresh data: '.$decodedResponse['Message'], 500);
    }
}
