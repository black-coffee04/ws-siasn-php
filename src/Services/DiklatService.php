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
        $this->config = $config;
        $this->httpClient = new HttpClient($this->config->getApiBaseUrl());
    }

    /**
     * Mendapatkan data riwayat diklat berdasarkan ID.
     *
     * @param string $idRiwayatDiklat ID riwayat diklat.
     * @return array Data riwayat diklat.
     */
    public function get(string $idRiwayatDiklat): array
    {
        $response = $this->httpClient->get(
            "/apisiasn/1.0/diklat/id/{$idRiwayatDiklat}", 
            ['headers' => $this->getHeaders()]
        );

        return $response['data'] ?? [];
    }

    /**
     * Membuat instance DiklatService dengan data yang diformat.
     *
     * @param array $data Data yang akan diformat dan disimpan.
     * @return self
     */
    public function create(array $data): self
    {
        $this->data = $this->formatDates($data, ['tanggal', 'tanggalSelesai']);
        return $this;
    }

    /**
     * Menyertakan dokumen dalam permintaan.
     *
     * @param mixed $file Dokumen yang akan diunggah.
     * @return self
     */
    public function includeDokumen($file)
    {
        $this->dokumen = $file;
        return $this;
    }

    /**
     * Menyimpan data riwayat diklat.
     *
     * @return string ID riwayat diklat yang disimpan atau pesan kesalahan.
     */
    public function save(): string
    {
        $response = $this->httpClient->post(
            "/apisiasn/1.0/diklat/save", 
            ['json' => $this->data, 'headers' => $this->getHeaders()]
        );

        if (!isset($response['mapData']['rwDiklatId'])) {
            return $response['message'];
        }

        if ($this->dokumen !== null && is_string($this->dokumen)) {
            $dokumenService = new DokumenService($this->authentication, $this->config);
            $dokumenService->uploadRiwayat($response['mapData']['rwDiklatId'], $this->idRefDokumenDiklat, $this->dokumen);
        }

        return $response['mapData']['rwDiklatId'] ?? $response['message'];
    }

    /**
     * Menghapus riwayat diklat berdasarkan ID.
     *
     * @param string $idRiwayatDiklat ID riwayat diklat yang akan dihapus.
     * @return bool Status keberhasilan penghapusan.
     * @throws SiasnDataException Jika riwayat diklat tidak ditemukan.
     */
    public function remove(string $idRiwayatDiklat): bool
    {
        $diklat = $this->get($idRiwayatDiklat);

        if (empty($diklat)) {
            throw new SiasnDataException('Riwayat diklat tidak ditemukan.');
        }

        $response = $this->httpClient->delete(
            "/apisiasn/1.0/diklat/delete/{$idRiwayatDiklat}", 
            ['headers' => $this->getHeaders()]
        );

        return $response['success'] ?? false;
    }

    /**
     * Memformat tanggal dalam data.
     *
     * @param array $data Data yang akan diformat.
     * @param array $dateKeys Kunci tanggal yang akan diformat.
     * @return array Data yang sudah diformat.
     */
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
            'Auth' => 'bearer ' . $this->getSsoAccessToken(),
            'Accept' => 'application/json',
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
