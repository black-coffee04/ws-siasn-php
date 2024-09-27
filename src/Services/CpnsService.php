<?php
namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Traits\ResponseTransformerTrait;

class CpnsService implements ServiceInterface
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
    private $skCPNS = null;

    /**
     * @var mixed Dokumen yang akan disertakan dalam permintaan.
     */
    private $spmt = null;

    /**
     * @var string ID referensi dokumen CPNS.
     */
    private $idRefDokumenCpns = '889';

    /**
     * @var string ID referensi dokumen SPMT.
     */
    private $idRefDokumenSPMT = '888';

    /**
     * Constructor untuk CpnsService.
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
     * Membuat permintaan CPNS baru.
     *
     * @param array $data Data CPNS.
     * @return $this
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
     * @return $this
     */
    public function includeDokumen($skCPNS, $spmt): self
    {
        $this->skCPNS = $skCPNS;
        $this->spmt   = $spmt;
        return $this;
    }

    /**
     * Menyimpan data CPNS.
     *
     * @return array ID riwayat CPNS atau pesan kesalahan.
     * @throws SiasnDataException Jika terjadi kesalahan saat menyimpan data.
     */
    public function save(): array
    {
        $response = $this->httpClient->post("/apisiasn/1.0/cpns/save", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        if (isset($response['mapData']) && is_array($response['mapData'])) {
            $keys      = array_keys($response['mapData']);
            $riwayatId = $keys[0] ?? null;
        
            if (!is_null($riwayatId)) {
                $riwayatIdValue = $response['mapData'][$riwayatId];
        
                foreach (['skCPNS', 'spmt'] as $dokumen) {
                    if (!is_null($this->$dokumen)) {
                        $this->uploadDokumen($riwayatIdValue, $this->$dokumen);
                    }
                }
            }
        }        

        return $this->transformResponse($response);
    }

    /**
     * Mengunggah dokumen terkait riwayat angka kredit.
     *
     * @param string $riwayatId ID riwayat angka kredit.
     * @return void
     */
    private function uploadDokumen(string $riwayatId, $dokumen): void
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $dokumenService->uploadRiwayat($riwayatId, $this->idRefDokumenCpns, $dokumen);
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
