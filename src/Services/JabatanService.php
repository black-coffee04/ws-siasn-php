<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

/**
 * Class JabatanService
 *
 * Layanan untuk mengelola data jabatan PNS.
 */
class JabatanService implements ServiceInterface
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
     * @var array|null Data yang akan dikirimkan dalam permintaan.
     */
    private $data;

    /**
     * @var mixed|null Dokumen yang akan disertakan dalam permintaan.
     */
    private $dokumen = null;

    /**
     * @var string ID referensi dokumen jabatan.
     */
    private $idRefDokumenJabatan = '872';

    /**
     * @var string Endpoint default untuk permintaan.
     */
    private $endPoint = 'jabatan/save';

    /**
     * Constructor untuk JabatanService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config = $config;
    }

    /**
     * Mendapatkan data jabatan PNS berdasarkan NIP.
     *
     * @param string $nip NIP PNS.
     * @return array Data PNS.
     */
    public function pns(string $nip): array
    {
        return $this->request("jabatan/pns", $nip);
    }

    /**
     * Mendapatkan riwayat jabatan berdasarkan ID riwayat.
     *
     * @param string $idRiwayat ID Riwayat.
     * @return array Data riwayat jabatan.
     */
    public function riwayat(string $idRiwayat): array
    {
        return $this->request("jabatan/id", $idRiwayat);
    }

    /**
     * Membuat Unor Jabatan baru.
     *
     * @param array $data Data Unor Jabatan.
     * @return $this
     */
    public function createUnorJabatan(array $data)
    {
        $this->data = $data;
        $this->endPoint = 'jabatan/unorjabatan/save';
        return $this;
    }

    /**
     * Menyertakan dokumen dalam pembuatan jabatan.
     *
     * @param mixed $file File dokumen yang akan diunggah.
     * @return $this
     */
    public function includeDokumen($file)
    {
        $this->dokumen  = $file;
        return $this;
    }

    /**
     * Membuat jabatan baru.
     *
     * @param array $data Data jabatan.
     * @return $this
     */
    public function create(array $data)
    {
        $this->data = $data;
        $this->endPoint = 'jabatan/save';
        return $this;
    }

    /**
     * Menyimpan data jabatan ke sistem.
     *
     * @return mixed ID Riwayat Jabatan atau pesan error.
     */
    public function save()
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->post("/apisiasn/1.0/{$this->endPoint}", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        if ($this->dokumen !== null && isset($response['mapData']['rwAngkaKreditId'])) {
           $this->uploadDokumen($response['mapData']['rwJabatanId']);
        }

        return $this->transformResponse($response);
    }

    /**
     * Mengunggah dokumen terkait riwayat jabatan.
     *
     * @param string $riwayatId ID riwayat jabatan.
     * @return void
     */
    private function uploadDokumen(string $riwayatId): void
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $dokumenService->uploadRiwayat($riwayatId, $this->idRefDokumenJabatan, $this->dokumen);
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
            ? ['id' => $response['mapData']['rwJabatanId'] ?? null] 
            : [];

        unset($response['mapData']);

        return $response;
    }

    /**
     * Menghapus riwayat jabatan berdasarkan ID.
     *
     * @param string $riwayatJabatanId ID Riwayat Jabatan.
     * @return array response.
     * @throws SiasnDataException Jika riwayat jabatan tidak ditemukan.
     */
    public function remove(string $riwayatJabatanId): array
    {
        $riwayatJabatan = $this->riwayat($riwayatJabatanId);
        if (empty($riwayatJabatan)) {
            throw new SiasnDataException('Riwayat Jabatan tidak ditemukan.');
        }

        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->delete("/apisiasn/1.0/jabatan/delete/{$riwayatJabatanId}", [
            'headers' => $this->getHeaders()
        ]);

        return $this->transformResponse($response);
    }

    /**
     * Mengirim permintaan HTTP ke endpoint API.
     *
     * @param string $endpoint Endpoint API yang dituju.
     * @param string $args Argumen yang diteruskan ke endpoint.
     * @return array Data respon dari API.
     * @throws SiasnDataException Jika terjadi kesalahan saat meminta data.
     */
    protected function request(string $endpoint, string $args): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->get("/apisiasn/1.0/{$endpoint}/{$args}", [
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
