<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

class JabatanService implements ServiceInterface
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
     * Constructor untuk ReferensiService.
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
     * Mendapatkan data jabatan PNS berdasarkan NIP.
     *
     * @param string $nip NIP PNS.
     * @return array Data PNS.
     */
    public function pns(string $nip): array
    {
        return $this->request("jabatan/pns", $nip);
    }

    /**
     * Mendapatkan riwayat jabatan berdasarkan ID riwayat.
     *
     * @param string $idRiwayat ID Riwayat.
     * @return array Data riwayat jabatan.
     */
    public function riwayat(string $idRiwayat): array
    {
        return $this->request("jabatan/id", $idRiwayat);
    }

    /**
     * Mengirim permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param string $args Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data.
     */
    protected function request(string $endpoint, string $args): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->get("/apisiasn/1.0/{$endpoint}/{$args}", [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
                'Accept'        => 'application/json'
            ]
        ]);

        return $response['data'] ?? [];
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
}