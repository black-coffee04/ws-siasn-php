<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Resources\Authentication;
use SiASN\Sdk\Resources\Pns;
use SiASN\Sdk\Resources\Referensi;

/**
 * Class SiasnClient.
 * 
 * Klien untuk berinteraksi dengan layanan SiASN menggunakan SDK.
 * 
 * @package SiASN\Sdk
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
     * Mendapatkan instance Authentication untuk mengambil token.
     *
     * @return Authentication Instance Authentication.
     */
    public function client(): Authentication
    {
        return new Authentication($this->config);
    }

    /**
     * Mendapatkan data referensi unit organisasi.
     *
     * @return Referensi Instance Referensi.
     */
    public function referensi(): Referensi
    {
        return new Referensi($this->config);
    }

    /**
     * Mendapatkan data Pns.
     *
     * @return Pns Instance Pns.
     */
    public function pns(): Pns
    {
        return new Pns($this->config);
    }
}
