<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Cache\Cache;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Exceptions\SiasnDataException;
use SiASN\Sdk\Exceptions\SiasnServiceException;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

/**
 * Class ReferensiService
 *
 * Service class for handling references.
 */
class ReferensiService implements ServiceInterface
{
    private const UNOR_CACHE_PREFIX = 'ref.unor.';
    private const DATA_PATH = __DIR__ . '/../Data/Referensi';

    /**
     * @var AuthenticationService Instance dari AuthenticationService untuk otentikasi.
     */
    private $authentication;

    /**
     * @var Config Instance dari Config yang menyimpan konfigurasi aplikasi.
     */
    private $config;

    /**
     * @var Cache Instance dari Cache untuk menyimpan data yang di-cache.
     */
    private $cache;

    /**
     * Constructor untuk ReferensiService.
     *
     * @param AuthenticationService $authentication Instance AuthenticationService untuk otentikasi.
     * @param Config $config Instance Config yang menyimpan konfigurasi aplikasi.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config = $config;
        $this->cache = new Cache();
    }

    /**
     * Magic method untuk memanggil data dari file JSON yang terkompresi.
     *
     * @param string $method Nama metode yang dipanggil (nama file JSON tanpa ekstensi).
     * @param array $args Argumen yang dilewatkan ke metode (tidak digunakan dalam kasus ini).
     * @return array Data dari file JSON yang terkompresi.
     * @throws SiasnServiceException Jika file JSON tidak ditemukan.
     */
    public function __call($method, $args)
    {
        $this->getWsoAccessToken();

        $fileName = $method . '.json.gz';
        $filePath = self::DATA_PATH . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($filePath)) {
            throw new SiasnServiceException("Method '$method' tidak ditemukan.");
        }

        $data = $this->getJsonData($filePath);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new SiasnServiceException("Gagal memproses data: $fileName");
        }

        return $data ?: [];
    }

    /**
     * Mengambil data JSON dari file yang terkompresi.
     *
     * @param string $filePath Lokasi file JSON yang terkompresi.
     * @return array|null Data dari file JSON atau null jika gagal.
     */
    public function getJsonData(string $filePath)
    {
        $compressedData = file_get_contents($filePath);
        $jsonData = gzdecode($compressedData);
        $data = json_decode($jsonData, true);
        return $data;
    }

    /**
     * Mengambil data UNOR dari API.
     *
     * @param bool $storeCache Menentukan apakah data akan disimpan di cache atau tidak.
     * @return array Data UNOR.
     * @throws SiasnDataException Jika data UNOR tidak ditemukan dalam respons.
     */
    public function unor(bool $storeCache = false): array
    {
        $cacheKey = self::UNOR_CACHE_PREFIX . $this->config->getClientId() . '-' . $this->config->getConsumerKey();

        if ($storeCache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $response = $this->request();

        if (!isset($response['data'])) {
            throw new SiasnDataException('Data UNOR tidak ditemukan.');
        }

        if ($storeCache) {
            $this->cache->set($cacheKey, $response['data']);
        }

        return $response['data'];
    }

    /**
     * Melakukan request ke API untuk mendapatkan data UNOR.
     *
     * @return array Respons dari API.
     */
    public function request(): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response = $httpClient->get('/apisiasn/1.0/referensi/ref-unor', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth' => 'bearer ' . $this->getSsoAccessToken(),
                'Accept' => 'application/json'
            ]
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
