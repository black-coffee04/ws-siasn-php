<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

class PemberhentianService implements ServiceInterface
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
     * Constructor untuk PemberhentianService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config = $config;
    }

    /**
     * Mengambil daftar pemberhentian berdasarkan rentang tanggal.
     *
     * @param string $tanggalAwal Tanggal awal rentang (format: Y-m-d).
     * @param string $tanggalAkhir Tanggal akhir rentang (format: Y-m-d).
     * @return array Data pemberhentian yang ditemukan.
     */
    public function get(string $tanggalAwal, string $tanggalAkhir): array
    {
        $tanggalAwalFormatted  = date('d-m-Y', strtotime($tanggalAwal));
        $tanggalAkhirFormatted = date('d-m-Y', strtotime($tanggalAkhir));

        $endpoint = '/apisiasn/1.0/pemberhentian/list';
        $args     = [
            'tglAwal'  => $tanggalAwalFormatted,
            'tglAkhir' => $tanggalAkhirFormatted,
        ];

        return $this->request($endpoint, $args);
    }

    /**
     * Melakukan permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param array $args Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     */
    protected function request(string $endpoint, array $args): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->get($endpoint, [
            'query'   => $args,
            'headers' => $this->getHeaders(),
        ]);

        return $response['data'] ?? [];
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
            'Content-Type'  => 'application/json',
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
}
