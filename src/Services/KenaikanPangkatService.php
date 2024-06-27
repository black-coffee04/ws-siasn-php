<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

class KenaikanPangkatService implements ServiceInterface
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
     * Constructor untuk KenaikanPangkatService.
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
     * Mengambil data kenaikan pangkat berdasarkan periode.
     *
     * @param string $periode Periode kenaikan pangkat (format: Y-m-d).
     * @return array Data kenaikan pangkat dari API.
     */
    public function get(string $periode): array
    {
        $endpoint = '/apisiasn/1.0/pns/list-kp-instansi';
        $query = [
            'periode' => date('Y-m-d', strtotime($periode))
        ];

        return $this->request($endpoint, $query);
    }

    /**
     * Melakukan permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param array $query Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     */
    protected function request(string $endpoint, array $query): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response = $httpClient->get($endpoint, [
            'query'   => $query,
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
