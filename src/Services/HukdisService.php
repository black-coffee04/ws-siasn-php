<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Resources\HttpClient;

class HukdisService implements ServiceInterface
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
     * @var HttpClient Instance dari HttpClient untuk request service.
     */
    private $httpClient;

    /**
     * @var array Data yang akan dikirimkan dalam permintaan.
     */
    private $data = [];

    /**
     * @var mixed Dokumen yang akan disertakan dalam permintaan.
     */
    private $dokumen = null;

    /**
     * @var string ID referensi dokumen hukdis.
     */
    private $idRefDokumenHukdis = '882';

    /**
     * Constructor untuk HukdisService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config         = $config;
        $this->httpClient     = new HttpClient($this->config->getApiBaseUrl());
    }

    /**
     * Mendapatkan data riwayat hukdis berdasarkan ID.
     *
     * @param string $idRiwayatHukdis ID riwayat hukdis.
     * @return array Data riwayat hukdis.
     */
    public function get(string $idRiwayatHukdis): array
    {
        $response = $this->httpClient->get(
            "/apisiasn/1.0/hukdis/id/{$idRiwayatHukdis}", 
            ['headers' => $this->getHeaders()]
        );

        return $response['data'] ?? [];
    }

    /**
     * Membuat instance HukdisService dengan data yang diberikan.
     *
     * @param array $data Data yang akan disimpan.
     * @return self
     */
    public function create(array $data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Menyertakan dokumen dalam permintaan.
     *
     * @param mixed $file Dokumen yang akan diunggah.
     * @return self
     */
    public function includeDokumen($file): self
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $this->dokumen  = $dokumenService->upload($this->idRefDokumenHukdis, $file);
        return $this;
    }

    /**
     * Menyimpan data riwayat hukdis.
     *
     * @return string ID riwayat hukdis yang disimpan atau pesan kesalahan.
     */
    public function save(): string
    {
        if ($this->dokumen !== null) {
            $this->data['path'] = [$this->dokumen];
        }

        $response = $this->httpClient->post(
            "/apisiasn/1.0/hukdis/save", 
            ['json' => $this->data, 'headers' => $this->getHeaders()]
        );

        return $response['mapData']['rwHukdisId'] ?? $response['message'];
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
