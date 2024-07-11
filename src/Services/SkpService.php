<?php
namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Resources\HttpClient;

class SkpService implements ServiceInterface
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
     * @var array|null Dokumen yang akan disertakan dalam permintaan.
     */
    private $dokumen = null;

    /**
     * @var string ID referensi dokumen SKP.
     */
    private $idRefDokumenSkp = '873';

    /**
     * @var string Endpoint untuk permintaan GET data SKP.
     */
    private $endPointGet = 'skp/id';

    /**
     * @var string Endpoint untuk permintaan POST data SKP.
     */
    private $endPointPost = 'skp/save';

    /**
     * Constructor untuk SkpService.
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
     * Mengambil data SKP berdasarkan tahun dan ID riwayat SKP.
     *
     * @param string $tahun Tahun data SKP (misalnya '2022').
     * @param string $idRiwayatSkp ID riwayat SKP.
     * @return array Array data SKP.
     */
    public function get(string $tahun, string $idRiwayatSkp): array
    {
        if ($tahun === '2022') {
            $this->endPointGet = 'skp22/id';
        }

        $response = $this->httpClient->get("/apisiasn/1.0/{$this->endPointGet}/{$idRiwayatSkp}", [
            'headers' => $this->getHeaders()
        ]);

        return $response['data'] ?? [];
    }

    /**
     * Menyiapkan data untuk membuat data SKP baru.
     *
     * @param string $tahun Tahun data SKP (misalnya '2022').
     * @param array $data Data yang akan dikirim dalam permintaan.
     * @return self
     */
    public function create(string $tahun, array $data): self
    {
        switch ($tahun) {
            case '2021':
                $this->endPointPost = 'skp/2021/save';
                break;
            case '2022':
                $this->endPointPost = 'skp22/save';
                break;
            default:
                $this->endPointPost = 'skp/save';
                break;
        }

        $this->data = $data;
        return $this;
    }

    /**
     * Menyertakan dokumen dalam pembuatan data SKP.
     *
     * @param mixed $file File dokumen yang akan disertakan.
     * @return self
     */
    public function includeDokumen($file): self
    {
        if ($this->endPointPost === 'skp22/save') {
            $dokumenService = new DokumenService($this->authentication, $this->config);
            $this->dokumen  = $dokumenService->upload($this->idRefDokumenSkp, $file);
        }
        return $this;
    }

    /**
     * Menyimpan data SKP yang telah disiapkan.
     *
     * @return array respon data SKP yang disimpan.
     */
    public function save(): array
    {
        if ($this->dokumen !== null) {
            $this->data['path'] = [$this->dokumen];
        }

        $response = $this->httpClient->post("/apisiasn/1.0/{$this->endPointPost}", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        return $response['mapData'] ?? $response;
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
