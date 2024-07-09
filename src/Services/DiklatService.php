<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Resources\HttpClient;

class DiklatService implements ServiceInterface
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
     * @var string ID referensi dokumen angka kredit.
     */
    private $idRefDokumenDiklat = '874';

    /**
     * Constructor untuk DiklatService.
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

    public function get(string $idRiwayatDiklat): array
    {
        $response = $this->httpClient->get("/apisiasn/1.0/diklat/id/{$idRiwayatDiklat}", [
            "headers" => $this->getHeaders()
        ]);
        
        return isset($response['data']) && is_array($response['data']) ? $response['data'] : [];
    }

    public function create(array $data): self
    {
        $this->data = $this->formatDates($data, ['tanggal', 'tanggalSelesai']);
        return $this;
    }

    public function includeDokumen($file): self
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $this->dokumen  = $dokumenService->upload($this->idRefDokumenDiklat, $file);
        return $this;
    }

    public function save(): string
    {
        if ($this->dokumen !== null && is_array($this->dokumen)) {
            $this->data['path'] = [$this->dokumen];
        }

        $response = $this->httpClient->post("/apisiasn/1.0/diklat/save", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        return $response['mapData']['rwDiklatId'] ?? $response['message'];
    }

    public function remove(string $idRiwayatDiklat)
    {
        $diklat = $this->get($idRiwayatDiklat);

        if (empty($diklat)) {
            throw new SiasnDataException('Riwayat diklat tidak ditemukan.');
        }

        $response = $this->httpClient->delete("/apisiasn/1.0/diklat/delete/{$idRiwayatDiklat}", [
            'headers' => $this->getHeaders()
        ]);

        return $response['success'] ?? false;
    }

    private function formatDates(array $data, array $dateKeys): array
    {
        foreach ($dateKeys as $key) {
            if (isset($data[$key])) {
                $data[$key] = date('d-m-Y', strtotime($data[$key]));
            }
        }
        return $data;
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
            'Accept'        => 'application/json'
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