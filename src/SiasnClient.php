<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Services\AuthenticationService;
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
     * Mendapatkan instance dari PnsService untuk mengakses data referensi.
     *
     * @return PnsService Instance dari PnsService.
     */
    public function pns(): PnsService
    {
        return new PnsService($this->authentication, $this->config);
    }
}
