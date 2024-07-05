<?php
namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;
use SiASN\Sdk\Exceptions\SiasnDataException;

class CpnsService implements ServiceInterface
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
     * @var string ID referensi dokumen CPNS.
     */
    private $idRefDokumenCpns = '889';

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
    public function includeDokumen($file): self
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $this->dokumen  = $dokumenService->upload($this->idRefDokumenCpns, $file);
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
        if ($this->dokumen !== null && is_array($this->dokumen)) {
            $this->data['path'] = [$this->dokumen];
        }

        $response = $this->httpClient->post("/apisiasn/1.0/cpns/save", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        return $response;
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
