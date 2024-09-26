<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Resources\HttpClient;

class AngkaKreditService implements ServiceInterface
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
     * @var mixed|null Dokumen yang akan disertakan dalam permintaan.
     */
    private $dokumen = null;

    /**
     * @var string ID referensi dokumen angka kredit.
     */
    private $idRefDokumenAngkaKredit = '879';

    /**
     * Constructor untuk AngkaKreditService.
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
     * Mendapatkan data angka kredit berdasarkan ID riwayat.
     *
     * @param string $idRiwayatAngkaKredit ID riwayat angka kredit.
     * @return array Data angka kredit.
     * @throws SiasnDataException Jika data tidak ditemukan atau terjadi kesalahan.
     */
    public function get(string $idRiwayatAngkaKredit): array
    {
        $response = $this->httpClient->get("/apisiasn/1.0/angkakredit/id/{$idRiwayatAngkaKredit}", [
            "headers" => $this->getHeaders()
        ]);
        
        return $response;
    }

    /**
     * Membuat permintaan angka kredit baru.
     *
     * @param array $data Data angka kredit.
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
     * @param mixed $file File dokumen yang akan diunggah.
     * @return self
     */
    public function includeDokumen($file): self
    {
        $this->dokumen = $file;
        return $this;
    }

    /**
     * Menyimpan data angka kredit.
     *
     * @return string ID riwayat angka kredit atau pesan kesalahan.
     * @throws SiasnDataException Jika terjadi kesalahan saat menyimpan data.
     */
    public function save(): array
    {
        $response = $this->httpClient->post("/apisiasn/1.0/angkakredit/save", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        if ($this->dokumen && isset($response['mapData']['rwAngkaKreditId'])) {
            $this->uploadDokumen($response['mapData']['rwAngkaKreditId']);
        }

        return $this->transformResponse($response);
    }

    /**
     * Transformasi respons dari API dengan mengubah kunci `mapData` menjadi `data`.
     *
     * @param array $response Respons asli dari API.
     * @return array Respons yang sudah ditransformasi.
     */
    private function transformResponse(array $response): array
    {
        $response['data'] = !empty($response['mapData']) && is_array($response['mapData'])
            ? ['id' => $response['mapData']['rwAngkaKreditId'] ?? null] 
            : [];

        unset($response['mapData']);

        return $response;
    }

    /**
     * Mengunggah dokumen terkait riwayat angka kredit.
     *
     * @param string $riwayatId ID riwayat angka kredit.
     * @return void
     */
    private function uploadDokumen(string $riwayatId): void
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $dokumenService->uploadRiwayat($riwayatId, $this->idRefDokumenAngkaKredit, $this->dokumen);
    }

    /**
     * Menghapus data angka kredit berdasarkan ID riwayat.
     *
     * @param string $idRiwayatAngkaKredit ID riwayat angka kredit.
     * @return bool Status keberhasilan penghapusan.
     * @throws SiasnDataException Jika data tidak ditemukan atau terjadi kesalahan.
     */
    public function remove(string $idRiwayatAngkaKredit): array
    {
        $angkaKredit = $this->get($idRiwayatAngkaKredit);

        if (empty($angkaKredit)) {
            throw new SiasnDataException('Riwayat angka kredit tidak ditemukan.');
        }

        $response = $this->httpClient->delete("/apisiasn/1.0/angkakredit/delete/{$idRiwayatAngkaKredit}", [
            'headers' => $this->getHeaders()
        ]);

        return $response;
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
