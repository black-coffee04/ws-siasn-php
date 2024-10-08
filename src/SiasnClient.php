<?php

namespace SiASN\Sdk;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Services\AngkaKreditService;
use SiASN\Sdk\Services\AuthenticationService;
use SiASN\Sdk\Services\CpnsService;
use SiASN\Sdk\Services\DiklatService;
use SiASN\Sdk\Services\DokumenService;
use SiASN\Sdk\Services\HukdisService;
use SiASN\Sdk\Services\JabatanService;
use SiASN\Sdk\Services\KenaikanPangkatService;
use SiASN\Sdk\Services\KinerjaPeriodikService;
use SiASN\Sdk\Services\KursusService;
use SiASN\Sdk\Services\PemberhentianService;
use SiASN\Sdk\Services\PengadaanService;
use SiASN\Sdk\Services\PenghargaanService;
use SiASN\Sdk\Services\PnsService;
use SiASN\Sdk\Services\ReferensiService;
use SiASN\Sdk\Services\RiwayatService;
use SiASN\Sdk\Services\SkpService;

/**
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
     * Constructor untuk SiasnClient.
     *
     * @param array $config Array konfigurasi untuk menginisialisasi SDK.
     */
    public function __construct(array $config = [])
    {
        $this->config = new Config($config);
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
     * Mendapatkan instance dari PnsService untuk mengakses data PNS.
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
     * Mendapatkan instance dari DokumenService untuk mengelola dokumen.
     *
     * @return DokumenService Instance dari DokumenService.
     */
    public function dokumen(): DokumenService
    {
        return new DokumenService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PemberhentianService untuk mengakses data pemberhentian.
     *
     * @return PemberhentianService Instance dari PemberhentianService.
     */
    public function pemberhentian(): PemberhentianService
    {
        return new PemberhentianService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PengadaanService untuk mengakses data pengadaan.
     *
     * @return PengadaanService Instance dari PengadaanService.
     */
    public function pengadaan(): PengadaanService
    {
        return new PengadaanService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari KenaikanPangkatService untuk mengakses data kenaikan pangkat.
     *
     * @return KenaikanPangkatService Instance dari KenaikanPangkatService.
     */
    public function kp(): KenaikanPangkatService
    {
        return new KenaikanPangkatService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari RiwayatService untuk mengakses data riwayat PNS.
     *
     * @return RiwayatService Instance dari RiwayatService.
     */
    public function riwayat(): RiwayatService
    {
        return new RiwayatService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari AngkaKreditService untuk mengelola angka kredit.
     *
     * @return AngkaKreditService Instance dari AngkaKreditService.
     */
    public function angkaKredit(): AngkaKreditService
    {
        return new AngkaKreditService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari CpnsService untuk mengelola data CPNS.
     *
     * @return CpnsService Instance dari CpnsService.
     */
    public function cpns(): CpnsService
    {
        return new CpnsService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari DiklatService untuk mengelola data diklat.
     *
     * @return DiklatService Instance dari DiklatService.
     */
    public function diklat(): DiklatService
    {
        return new DiklatService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari HukdisService untuk mengelola data hukuman disiplin.
     *
     * @return HukdisService Instance dari HukdisService.
     */
    public function hukdis(): HukdisService
    {
        return new HukdisService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari KinerjaPeriodikService untuk mengelola data kinerja periodik.
     *
     * @return KinerjaPeriodikService Instance dari KinerjaPeriodikService.
     */
    public function kinerjaPeriodik(): KinerjaPeriodikService
    {
        return new KinerjaPeriodikService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari KursusService untuk mengelola data kursus.
     *
     * @return KursusService Instance dari KursusService.
     */
    public function kursus(): KursusService
    {
        return new KursusService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari SkpService untuk mengelola data skp.
     *
     * @return SkpService Instance dari SkpService.
     */
    public function skp(): SkpService
    {
        return new SkpService($this->authentication, $this->config);
    }

    /**
     * Mendapatkan instance dari PenghargaanService untuk mengelola data penghargaan.
     *
     * @return SkpService Instance dari SkpService.
     */
    public function penghargaan(): PenghargaanService
    {
        return new PenghargaanService($this->authentication, $this->config);
    }
}
