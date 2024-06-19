<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;

/**
 * Class Referensi
 *
 * Kelas ini digunakan untuk mengakses data referensi dari layanan SiASN.
 */
class Referensi extends Authentication
{
    private const UNOR_CACHE_PREFIX = 'ref.unor.';
    private const DATA_PATH = __DIR__ . '/../Data/Referensi';
    private const UNOR_ENDPOINT = '/referensi/ref-unor';

    /**
     * Membuat instance Referensi.
     *
     * @param Config $config Objek konfigurasi.
     */
    public function __construct(Config $config)
    {
        parent::__construct($config);
    }

    /**
     * Magic method untuk memanggil data dari file JSON yang terkompresi.
     *
     * @param string $method Nama metode yang dipanggil (nama file JSON tanpa ekstensi).
     * @param array $args Argumen yang dilewatkan ke metode (tidak digunakan dalam kasus ini).
     * @return array Data dari file JSON yang terkompresi.
     * @throws SiasnRequestException Jika file JSON tidak ditemukan.
     */
    public function __call($method, $args)
    {
        $this->getWsoAccessToken();

        $fileName = $method . '.json.gz';
        $filePath = self::DATA_PATH . DIRECTORY_SEPARATOR . $fileName;

        if (!file_exists($filePath)) {
            throw new SiasnRequestException("Method '$method' tidak ditemukan.");
        }

        $compressedData = file_get_contents($filePath);
        $jsonData = gzdecode($compressedData);
        $data = json_decode($jsonData, true);

        if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new SiasnRequestException("Gagal memproses data: $fileName");
        }

        return $data ?: [];
    }

    /**
     * Mengambil data UNOR.
     *
     * @param bool $storeCache Menentukan apakah data akan disimpan di cache atau tidak.
     * @return array Data UNOR.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data UNOR.
     */
    public function unor(bool $storeCache = false): array
    {
        $cacheKey = self::UNOR_CACHE_PREFIX . $this->config->getClientId() . '-' . $this->config->getConsumerKey();

        if ($storeCache && $this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $response = $this->requestUnorData();
        
        if ($storeCache) {
            $this->cache->set($cacheKey, $response);
        }
        
        return $response;
    }

    /**
     * Meminta data UNOR dari layanan SiASN.
     *
     * @return array Data UNOR.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data UNOR.
     */
    private function requestUnorData(): array
    {
        $requestOptions = [
            'url' => $this->config->getApiBaseUrl() . self::UNOR_ENDPOINT,
            'headers' => [
                'Accept: application/json',
                'Auth: bearer ' . $this->getSsoAccessToken(),
                'Authorization: Bearer ' . $this->getWsoAccessToken(),
            ],
        ];

        $response = $this->get($requestOptions)->getBody();

        if (!isset($response['data'])) {
            throw new SiasnRequestException('Data UNOR tidak ditemukan.', 404);
        }

        return $response['data'];
    }
}
