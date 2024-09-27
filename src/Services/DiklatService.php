<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Traits\ResponseTransformerTrait;

class DiklatService implements ServiceInterface
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
     * @var HttpClient Instance dari HttpClient untuk mengirim permintaan ke API.
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
     * @var string ID referensi dokumen diklat.
     */
    private $idRefDokumenDiklat = '874';

    /**
     * Konstruktor untuk DiklatService.
     *
     * Menginisialisasi layanan diklat dengan otentikasi dan konfigurasi yang diberikan.
     *
     * @param AuthenticationService $authentication Instance dari AuthenticationService untuk otentikasi.
     * @param Config $config Instance dari Config yang menyimpan konfigurasi aplikasi.
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
     * @throws SiasnDataException Jika data tidak ditemukan.
     */
    public function get(string $idRiwayatDiklat): array
    {
        $response = $this->httpClient->get(
            "/apisiasn/1.0/diklat/id/{$idRiwayatDiklat}", 
            ['headers' => $this->getHeaders()]
        );

        return $this->transformResponse($response, 'rwDiklatId');
    }

    /**
     * Membuat instance DiklatService dengan data yang diformat.
     *
     * @param array $data Data yang akan diformat dan disimpan.
     * @return self Instance dari DiklatService.
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
     * @return self Instance dari DiklatService.
     */
    public function includeDokumen($file): self
    {
        $this->dokumen = $file;
        return $this;
    }

    /**
     * Menyimpan data riwayat diklat.
     *
     * @return array ID riwayat diklat yang disimpan.
     */
    public function save(): array
    {
        $response = $this->httpClient->post(
            "/apisiasn/1.0/diklat/save", 
            ['json' => $this->data, 'headers' => $this->getHeaders()]
        );

        if ($this->dokumen && isset($response['mapData']['rwDiklatId'])) {
            $this->uploadDokumen($response['mapData']['rwDiklatId']);
        }

        return $this->transformResponse($response, 'rwDiklatId');
    }

    /**
     * Mengunggah dokumen terkait riwayat diklat.
     *
     * @param string $riwayatId ID riwayat diklat.
     * @return void
     */
    private function uploadDokumen(string $riwayatId): void
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $dokumenService->uploadRiwayat($riwayatId, $this->idRefDokumenDiklat, $this->dokumen);
    }

    /**
     * Menghapus riwayat diklat berdasarkan ID.
     *
     * @param string $idRiwayatDiklat ID riwayat diklat yang akan dihapus.
     * @return array Status keberhasilan penghapusan.
     * @throws SiasnDataException Jika riwayat diklat tidak ditemukan.
     */
    public function remove(string $idRiwayatDiklat): array
    {
        $diklat = $this->get($idRiwayatDiklat);

        if (empty($diklat)) {
            throw new SiasnDataException('Riwayat diklat tidak ditemukan.');
        }

        $response = $this->httpClient->delete(
            "/apisiasn/1.0/diklat/delete/{$idRiwayatDiklat}", 
            ['headers' => $this->getHeaders()]
        );

        return $this->transformResponse($response, 'rwDiklatId');
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
