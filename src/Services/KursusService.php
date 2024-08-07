<?php
namespace SiASN\Sdk\Services;

use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Resources\HttpClient;

class KursusService implements ServiceInterface
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
     * @var string ID referensi dokumen angka kredit.
     */
    private $idRefDokumenKursus = '874';

    /**
     * Constructor untuk KursusService.
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
     * Mengambil data kursus berdasarkan ID.
     *
     * @param string $idRiwayatKursus ID riwayat kursus.
     * @return array Array data kursus.
     */
    public function get(string $idRiwayatKursus): array
    {
        $response = $this->httpClient->get("/apisiasn/1.0/kursus/id/{$idRiwayatKursus}", [
            "headers" => $this->getHeaders()
        ]);
        
        return isset($response['data']) && is_array($response['data']) ? $response['data'] : [];
    }

    /**
     * Menyiapkan data untuk membuat kursus.
     *
     * @param array $data Data yang akan dikirim dalam permintaan.
     * @return self
     */
    public function create(array $data): self
    {
        $this->data = $this->formatDates($data, ['tanggalKursus', 'tanggalSelesaiKursus']);;
        return $this;
    }

    /**
     * Menyertakan dokumen dalam pembuatan kursus.
     *
     * @param mixed $file File dokumen yang akan disertakan.
     * @return self
     */
    public function includeDokumen($file): self
    {
        $dokumenService = new DokumenService($this->authentication, $this->config);
        $this->dokumen  = $dokumenService->upload($this->idRefDokumenKursus, $file);
        return $this;
    }

    /**
     * Menyimpan data kursus.
     *
     * @return string Pesan atau ID kursus yang disimpan.
     */
    public function save(): string
    {
        if ($this->dokumen !== null && is_array($this->dokumen)) {
            $this->data['path'] = [$this->dokumen];
        }

        $response = $this->httpClient->post("/apisiasn/1.0/kursus/save", [
            'json'    => $this->data,
            'headers' => $this->getHeaders()
        ]);

        return $response['mapData']['rwKursusId'] ?? $response['message'];
    }

    /**
     * Menghapus kursus berdasarkan ID.
     *
     * @param string $idRiwayatKursus ID riwayat kursus yang akan dihapus.
     * @return bool True jika penghapusan berhasil, false jika tidak.
     * @throws SiasnDataException Jika riwayat kursus tidak ditemukan.
     */
    public function remove(string $idRiwayatKursus): bool
    {
        $angkaKredit = $this->get($idRiwayatKursus);

        if (empty($angkaKredit)) {
            throw new SiasnDataException('Riwayat kursus tidak ditemukan.');
        }

        $response = $this->httpClient->delete("/apisiasn/1.0/kursus/delete/{$idRiwayatKursus}", [
            'headers' => $this->getHeaders()
        ]);

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
