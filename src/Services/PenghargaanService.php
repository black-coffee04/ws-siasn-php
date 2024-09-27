<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Traits\ResponseTransformerTrait;

class PenghargaanService implements ServiceInterface
{
    use ResponseTransformerTrait; 

    private $authentication;
    private $config;
    private $httpClient;
    private $data = [];
    private $dokumen = null;
    private $idRefDokumenPenghargaan = '892';

    /**
     * Constructor untuk PenghargaanService.
     *
     * @param AuthenticationService $authentication Instance dari AuthenticationService untuk otentikasi.
     * @param Config $config Instance dari Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config         = $config;
        $this->httpClient     = new HttpClient($this->config->getApiBaseUrl());
    }

    /**
     * Mendapatkan riwayat penghargaan berdasarkan ID.
     *
     * @param string $idRiwayatPenghargaan ID riwayat penghargaan.
     * @return array Data riwayat penghargaan yang sudah ditransformasi.
     */
    public function get(string $idRiwayatPenghargaan): array
    {
        $response = $this->httpClient->get("/apisiasn/1.0/penghargaan/id/{$idRiwayatPenghargaan}", [
            'headers' => $this->getHeaders()
        ]);

        return $this->transformResponse($response, 'rwPenghargaanId');
    }

    /**
     * Membuat instance PenghargaanService dengan data yang diberikan.
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
        $this->dokumen = $file;
        return $this;
    }

    /**
     * Menyimpan data penghargaan dan mengunggah dokumen jika diperlukan.
     *
     * @return array Respons yang sudah ditransformasi dari API.
     * @throws SiasnDataException Jika terjadi kesalahan saat menyimpan data.
     */
    public function save(): array
    {
        $response = $this->httpClient->post('/apisiasn/1.0/penghargaan/save', [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        if ($this->dokumen && !empty($response['mapData']['rwPenghargaanId'])) {
            $this->uploadDokumen($response['mapData']['rwPenghargaanId']);
        }

        return $this->transformResponse($response, 'rwPenghargaanId');
    }

    /**
     * Menghapus riwayat penghargaan berdasarkan ID.
     *
     * @param string $riwayatPenghargaanId ID riwayat penghargaan yang akan dihapus.
     * @return array Respons yang sudah ditransformasi dari API.
     * @throws SiasnDataException Jika riwayat penghargaan tidak ditemukan.
     */
    public function remove(string $riwayatPenghargaanId): array
    {
        $this->get($riwayatPenghargaanId);
        $response = $this->httpClient->delete("/apisiasn/1.0/penghargaan/delete/{$riwayatPenghargaanId}", [
            'headers' => $this->getHeaders()
        ]);

        return $this->transformResponse($response, 'rwPenghargaanId');
    }

    /**
     * Mengunggah dokumen terkait riwayat penghargaan.
     *
     * @param string $riwayatPenghargaanId ID riwayat penghargaan.
     * @return void
     */
    private function uploadDokumen(string $riwayatPenghargaanId): void
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $dokumenService->uploadRiwayat($riwayatPenghargaanId, $this->idRefDokumenPenghargaan, $this->dokumen);
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
