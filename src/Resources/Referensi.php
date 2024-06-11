<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\RestRequestException;
use SiASN\Sdk\Resources\Authentication;

class Referensi extends Authentication
{
    /**
     * Membuat instance Referensi.
     *
     * @param \SiASN\Sdk\Config $config Objek konfigurasi.
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
     * @throws RestRequestException Jika terjadi kesalahan saat meminta data UNOR.
     */
    public function unor($storeCache = false): array
    {
        try {
            $cacheKey = 'ref.unor.' . $this->config->getClientId();

            if ($storeCache && $this->cache->has($cacheKey)) {
                return $this->cache->get($cacheKey);
            }

            $requestOptions = [
                'url'     => $this->config->getApiBaseUrl() . '/referensi/ref-unor',
                'headers' => [
                    'Accept: application/json',
                    'Auth: bearer ' . $this->ssoAccessToken(),
                    'Authorization: Bearer ' . $this->wsoAccessToken(),
                ]
            ];

            $response = $this->get($requestOptions);
            $decodedResponse = json_decode($response, true);

            if ($storeCache) {
                $this->cache->set($cacheKey, $decodedResponse['data']);
            }

            return $decodedResponse['data'] ?? [];
        } catch (RestRequestException $e) {
            throw new RestRequestException('Gagal mengambil data UNOR: ' . $e->getMessage(), $e->getCode());
        }
    }
}
