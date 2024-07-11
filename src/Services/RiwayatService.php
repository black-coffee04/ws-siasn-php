<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

class RiwayatService implements ServiceInterface
{
    /**
     * @var AuthenticationService Instance dari AuthenticationService untuk otentikasi.
     */
    private $authentication;

    /**
     * @var Config Instance dari Config yang menyimpan konfigurasi aplikasi.
     */
    private $config;

    /**
     * Konstruktor untuk RiwayatService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config         = $config;
    }

    /**
     * Mengirim permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param string $args Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     */
    protected function request(string $endpoint, string $args): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->get("/apisiasn/1.0/{$endpoint}/{$args}", [
            'headers' => $this->getHeaders()
        ]);

        return $response['data'] && !is_string($response['data']) ? $response['data'] : [];
    }

    /**
     * Mendapatkan header untuk permintaan HTTP.
     *
     * @return array Header untuk permintaan HTTP.
     */
    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
            'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
            'Accept'        => 'application/json',
            'Content-Type'  => 'application/json'
        ];
    }

    /**
     * Mendapatkan access token dari layanan SSO.
     *
     * @return string Access token SSO.
     */
    public function getSsoAccessToken(): string
    {
        return $this->authentication->getSsoAccessToken();
    }

    /**
     * Mendapatkan access token dari layanan WSO.
     *
     * @return string Access token WSO.
     */
    public function getWsoAccessToken(): string
    {
        return $this->authentication->getWsoAccessToken();
    }

    /**
     * Mendapatkan data angka kredit berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data angka kredit.
     */
    public function angkaKredit(string $nip): array
    {
        return $this->request("pns/rw-angkakredit", $nip);
    }

    /**
     * Mendapatkan data CLTN berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data CLTN.
     */
    public function cltn(string $nip): array
    {
        return $this->request("pns/rw-cltn", $nip);
    }

    /**
     * Mendapatkan data diklat berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data diklat.
     */
    public function diklat(string $nip): array
    {
        return $this->request("pns/rw-diklat", $nip);
    }

    /**
     * Mendapatkan data DP3 berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data DP3.
     */
    public function dp3(string $nip): array
    {
        return $this->request("pns/rw-dp3", $nip);
    }

    /**
     * Mendapatkan data golongan berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data golongan.
     */
    public function golongan(string $nip): array
    {
        return $this->request("pns/rw-golongan", $nip);
    }

    /**
     * Mendapatkan data hukdis berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data hukdis.
     */
    public function hukdis(string $nip): array
    {
        return $this->request("pns/rw-hukdis", $nip);
    }

    /**
     * Mendapatkan data jabatan berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data jabatan.
     */
    public function jabatan(string $nip): array
    {
        return $this->request("pns/rw-jabatan", $nip);
    }

    /**
     * Mendapatkan data kinerja periodik berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data kinerja periodik.
     */
    public function kinerjaPeriodik(string $nip): array
    {
        return $this->request("pns/rw-kinerjaperiodik", $nip);
    }

    /**
     * Mendapatkan data kursus berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data kursus.
     */
    public function kursus(string $nip): array
    {
        return $this->request("pns/rw-kursus", $nip);
    }

    /**
     * Mendapatkan data masa kerja berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data masa kerja.
     */
    public function masaKerja(string $nip): array
    {
        return $this->request("pns/rw-masakerja", $nip);
    }

    /**
     * Mendapatkan data pemberhentian berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data pemberhentian.
     */
    public function pemberhentian(string $nip): array
    {
        return $this->request("pns/rw-pemberhentian", $nip);
    }

    /**
     * Mendapatkan data pendidikan berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data pendidikan.
     */
    public function pendidikan(string $nip): array
    {
        return $this->request("pns/rw-pendidikan", $nip);
    }

    /**
     * Mendapatkan data penghargaan berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data penghargaan.
     */
    public function penghargaan(string $nip): array
    {
        return $this->request("pns/rw-penghargaan", $nip);
    }

    /**
     * Mendapatkan data pindah instansi berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data pindah instansi.
     */
    public function pindahInstansi(string $nip): array
    {
        return $this->request("pns/rw-pindahinstansi", $nip);
    }

    /**
     * Mendapatkan data unor berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data unor.
     */
    public function unor(string $nip): array
    {
        return $this->request("pns/rw-pnsunor", $nip);
    }

    /**
     * Mendapatkan data PWK berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data PWK.
     */
    public function pwk(string $nip): array
    {
        return $this->request("pns/rw-pwk", $nip);
    }

    /**
     * Mendapatkan data SKP berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data SKP.
     */
    public function skp(string $nip): array
    {
        return $this->request("pns/rw-skp", $nip);
    }

    /**
     * Mendapatkan data SKP22 berdasarkan NIP.
     *
     * @param string $nip Nomor Induk Pegawai.
     * @return array Data SKP22.
     */
    public function skp22(string $nip): array
    {
        return $this->request("pns/rw-skp22", $nip);
    }
}
