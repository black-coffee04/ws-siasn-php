<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;
use SiASN\Sdk\Resources\Authentication;

/**
 * Class Referensi.
 *
 * Kelas ini digunakan untuk mengakses data referensi dari layanan SiASN.
 */
class Referensi extends Authentication
{
    private const UNOR_CACHE_PREFIX = 'ref.unor.';

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
     * Mengambil data UNOR.
     *
     * @param bool $storeCache Menentukan apakah data akan disimpan di cache atau tidak.
     * @return array Data UNOR.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta data UNOR.
     */
    public function getUnor(bool $storeCache = false): array
    {
        try {
            $cacheKey = self::UNOR_CACHE_PREFIX . $this->config->getClientId();
            if ($storeCache && $this->cache->has($cacheKey)) {
                return $this->cache->get($cacheKey);
            }

            $response = $this->requestUnorData();

            if ($storeCache) {
                $this->cache->set($cacheKey, $response);
            }

            return $response;
        } catch (SiasnRequestException $e) {
            throw new SiasnRequestException('Gagal mengambil data UNOR: ' . $e->getMessage(), $e->getCode());
        }
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
            'url'     => $this->config->getApiBaseUrl() . '/referensi/ref-unor',
            'headers' => [
                'Accept: application/json',
                'Auth: bearer ' . $this->getSsoAccessToken(),
                'Authorization: Bearer ' . $this->getWsoAccessToken(),
            ],
        ];

        $response = $this->get($requestOptions)->getBody();

        return $response['data'] ?? [];
    }
}
