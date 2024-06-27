<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Services\AuthenticationService;
use SiASN\Sdk\Services\DokumenService;
use SiASN\Sdk\Services\JabatanService;
use SiASN\Sdk\Services\KenaikanPangkatService;
use SiASN\Sdk\Services\PemberhentianService;
use SiASN\Sdk\Services\PengadaanService;
use SiASN\Sdk\Services\PnsService;
use SiASN\Sdk\Services\ReferensiService;

/**
 * Class SiasnClient
 *
 * Kelas utama untuk berinteraksi dengan SDK SiASN.
 */
class SiasnClient
{
    /**
     * @var Config Instance dari Config untuk konfigurasi SDK.
     */
    private $config;

    /**
     * @var AuthenticationService Instance dari AuthenticationService untuk otentikasi.
     */
    private $authentication;

    /**
     * SiasnClient constructor.
     *
     * @param array $config Array konfigurasi untuk menginisialisasi SDK.
     */
    public function __construct(array $config = [])
    {
        $this->config         = new Config($config);
        $this->authentication = new AuthenticationService($this->config);
    }

    /**
     * Mendapatkan instance dari AuthenticationService untuk mengelola otentikasi.
     *
     * @return AuthenticationService Instance dari AuthenticationService.
     */
    public function authentication(): AuthenticationService
    {
        return $this->authentication;
    }

    /**
     * Mendapatkan instance dari ReferensiService untuk mengakses data referensi.
     *
     * @return ReferensiService Instance dari ReferensiService.
     */
    public function referensi(): ReferensiService
    {
        return new ReferensiService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PnsService untuk mengakses data pns.
     *
     * @return PnsService Instance dari PnsService.
     */
    public function pns(): PnsService
    {
        return new PnsService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari JabatanService untuk mengakses data jabatan.
     *
     * @return JabatanService Instance dari JabatanService.
     */
    public function jabatan(): JabatanService
    {
        return new JabatanService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari DokumenService untuk mengakses data jabatan.
     *
     * @return DokumenService Instance dari DokumenService.
     */
    public function dokumen(): DokumenService
    {
        return new DokumenService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PemberhentianService untuk mengakses data jabatan.
     *
     * @return PemberhentianService Instance dari PemberhentianService.
     */
    public function pemberhentian(): PemberhentianService
    {
        return new PemberhentianService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PemberhentianService untuk mengakses data jabatan.
     *
     * @return PengadaanService Instance dari PemberhentianService.
     */
    public function pengadaan(): PengadaanService
    {
        return new PengadaanService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PemberhentianService untuk mengakses data jabatan.
     *
     * @return KenaikanPangkatService Instance dari PemberhentianService.
     */
    public function kp(): KenaikanPangkatService
    {
        return new KenaikanPangkatService($this->authentication, $this->config);
    }
}
