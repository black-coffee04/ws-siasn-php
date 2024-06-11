<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\RestRequestException;


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
    private function validateNip(string $nip)
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
        try {
            $this->validateNip($nip);

            $requestOptions = [
                'url'     => $this->config->getApiBaseUrl() . $endpoint . $nip,
                'headers' => [
                    'Accept: application/json',
                    'Auth: bearer ' . $this->ssoAccessToken(),
                    'Authorization: Bearer ' . $this->wsoAccessToken(),
                ]
            ];

            $response = $this->get($requestOptions);
            $decodedResponse = json_decode($response, true);

            return $decodedResponse['data'] ?? [];
        } catch (RestRequestException $e) {
            throw new RestRequestException('Gagal mengambil data: ' . $e->getMessage(), $e->getCode());
        }
    }

    /**
     * Mengambil data utama PNS berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data utama PNS.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data.
     */
    public function dataUtama(string $nip = ''): array
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
    public function dataPasangan(string $nip = ''): array
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
    public function dataAnak(string $nip = ''): array
    {
        return $this->fetchData('/pns/data-anak/', $nip);
    }
}
