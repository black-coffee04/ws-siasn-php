<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Config;
use SiASN\Sdk\Resources\Authentication;
use SiASN\Sdk\Resources\PNS;
use SiASN\Sdk\Resources\Referensi;

/**
 * Class SiasnClient.
 * 
 * Klien untuk berinteraksi dengan layanan SiASN menggunakan SDK.
 *
 * @author  Black Coffee 04
 * @license MIT License
 */

class SiasnClient
{
    /** @var Config Konfigurasi yang digunakan oleh klien. */
    private $config;

    /**
     * Constructor untuk SiasnClient.
     *
     * @param array $config Konfigurasi yang digunakan untuk inisialisasi.
     */
    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
    }

    /**
     * Mengembalikan objek konfigurasi yang digunakan oleh klien.
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
     * @return object Data referensi unit organisasi.
     */
    public function referensi(): object
    {
        return new Referensi($this->config);
    }

    /**
     * Mendapatkan data PNS.
     *
     * @return object Data PNS.
     */
    public function pns(): object
    {
        return new PNS($this->config);
    }
}
