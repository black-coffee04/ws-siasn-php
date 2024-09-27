<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Traits\ResponseTransformerTrait;

class PengadaanService implements ServiceInterface
{
    use ResponseTransformerTrait;

    /**
     * @var AuthenticationService Instance dari AuthenticationService untuk otentikasi.
     */
    private $authentication;

    /**
     * @var Config Instance dari Config yang menyimpan konfigurasi aplikasi.
     */
    private $config;

    /**
     * Constructor untuk PengadaanService.
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
     * Mengambil data pengadaan berdasarkan tahun.
     *
     * @param string $tahun Tahun pengadaan.
     * @return array Data pengadaan dari API.
     */
    public function get(string $tahun): array
    {
        $endpoint = '/apisiasn/1.0/pengadaan/list-pengadaan-instansi';
        $query    = [
            'tahun' => $tahun
        ];

        return $this->request($endpoint, $query);
    }

    /**
     * Mengambil dokumen pengadaan.
     *
     * @return array Data dokumen pengadaan dari API.
     */
    public function dokumen(): array
    {
        $endpoint = '/apisiasn/1.0/pengadaan/dokumen-pengadaan';
        return $this->request($endpoint, []);
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
        $response   = $httpClient->get($endpoint, [
            'query'   => $query,
            'headers' => $this->getHeaders(),
        ]);

        return $this->transformResponse($response);
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
