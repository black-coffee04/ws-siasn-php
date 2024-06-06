<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Config;
use SiASN\Sdk\Resources\Authentication;
use SiASN\Sdk\Resources\Referensi;

class SiasnClient
{
    private $config;

    /**
     * Membuat instance SiasnClient.
     *
     * @param array $config Konfigurasi yang digunakan untuk inisialisasi.
     */
    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * Mengembalikan objek konfigurasi yang digunakan oleh client.
     *
     * @return Config Objek konfigurasi.
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * Mengambil token dari WSO menggunakan metode client_credentials.
     *
     * @return string Token dari WSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function wsoAccessToken(): string
    {
        return (new Authentication($this->config))->wsoAccessToken();
    }

    /**
     * Mengambil token dari SSO menggunakan metode password.
     *
     * @return string Token dari SSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function ssoAccessToken(): string
    {
        return (new Authentication($this->config))->ssoAccessToken();
    }

    /**
     * Mendapatkan data referensi unit organisasi.
     *
     * @param bool $cache Menentukan apakah data akan disimpan di cache atau tidak.
     * @return array Data referensi unit organisasi.
     */
    public function getReferensiUnor(bool $cache = false): array
    {
        return (new Referensi($this->config))->unor($cache);
    }
}
