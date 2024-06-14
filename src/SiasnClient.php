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
     * Mengembalikan instance Authentication untuk mengelola otentikasi.
     *
     * @return Authentication Instance Authentication.
     */
    public function authentication(): Authentication
    {
        return new Authentication($this->config);
    }

    /**
     * Mengembalikan instance Referensi untuk mengelola data referensi.
     *
     * @return Referensi Instance Referensi.
     */
    public function referensi(): Referensi
    {
        return new Referensi($this->config);
    }

    /**
     * Mengembalikan instance Pns untuk mengelola data PNS.
     *
     * @return Pns Instance Pns.
     */
    public function pns(): Pns
    {
        return new Pns($this->config);
    }
}
