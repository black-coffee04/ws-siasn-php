<?php
namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Resources\HttpClient;

class KinerjaPeriodikService implements ServiceInterface
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
     * @var string ID referensi dokumen kinerja.
     */
    private $idRefDokumenKinerjaPeriodik = '890';

    /**
     * Constructor untuk KinerjaPeriodikService.
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
     * Menetapkan data untuk permintaan.
     *
     * @param array $data Data yang akan dikirim.
     * @return self Instance dari KinerjaPeriodikService.
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
     * @return self Instance dari KinerjaPeriodikService.
     */
    public function includeDokumen($file): self
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $this->dokumen  = $dokumenService->upload($this->idRefDokumenKinerjaPeriodik, $file);
        return $this;
    }

    /**
     * Menyimpan data kinerja periodik.
     *
     * @return string Pesan atau mapData dari response.
     */
    public function save(): string
    {
        if ($this->dokumen !== null && is_array($this->dokumen)) {
            $this->data['path'] = [$this->dokumen];
        }

        $response = $this->httpClient->post("/apisiasn/1.0/kinerjaperiodik/save", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        return $response['mapData'] ?? $response['message'];
    }

    /**
     * Menghapus riwayat kinerja periodik.
     *
     * @param string $idRiwayatKinerjaPeriodik ID riwayat kinerja periodik yang akan dihapus.
     * @return bool Status keberhasilan penghapusan.
     */
    public function remove(string $idRiwayatKinerjaPeriodik): bool
    {
        $response = $this->httpClient->delete("/apisiasn/1.0/kinerjaperiodik/delete/{$idRiwayatKinerjaPeriodik}", [
            'headers' => $this->getHeaders()
        ]);

        return $response['success'] ?? false;
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
}
