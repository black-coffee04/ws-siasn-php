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
     * @var AuthenticationService Instance of AuthenticationService for authentication.
     */
    private $authentication;

    /**
     * @var Config Instance of Config that holds the application configuration.
     */
    private $config;

    /**
     * @var Cache Instance of Cache for storing cached data.
     */
    private $cache;

    /**
     * Constructor for ReferensiService.
     *
     * @param AuthenticationService $authentication Instance of AuthenticationService for authentication.
     * @param Config $config Instance of Config that holds the application configuration.
     */
    public function __construct(AuthenticationService $authentication, Config $config)
    {
        $this->authentication = $authentication;
        $this->config = $config;
        $this->cache = new Cache();
    }

    /**
     * Magic method to retrieve data from compressed JSON file.
     *
     * @param string $method Method name being called (JSON file name without extension).
     * @param array $args Arguments passed to the method (not used in this case).
     * @return QueryBuilder QueryBuilder instance with data from JSON file.
     * @throws SiasnServiceException If JSON file is not found.
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

        return (new QueryBuilder($data, true));
    }

    /**
     * Retrieve JSON data from compressed file.
     *
     * @param string $filePath Path to the compressed JSON file.
     * @return array|null Data from the JSON file or null if failed.
     */
    public function getJsonData(string $filePath)
    {
        return json_decode(gzdecode(file_get_contents($filePath)), true);
    }

    /**
     * Retrieve UNOR data.
     *
     * @param bool $storeCache Determines whether to store data in cache or not.
     * @return QueryBuilder QueryBuilder instance with UNOR data.
     * @throws SiasnDataException If UNOR data is not found in the response.
     */
    public function unor(bool $storeCache = false): QueryBuilder
    {
        $cacheKey = self::UNOR_CACHE_PREFIX . $this->config->getClientId() . '-' . $this->config->getConsumerKey();

        if ($storeCache && $this->cache->has($cacheKey)) {
            return new QueryBuilder($this->cache->get($cacheKey));
        }

        $response = $this->request();

        if (!isset($response['data'])) {
            throw new SiasnDataException('Data UNOR tidak ditemukan.');
        }

        if ($storeCache) {
            $this->cache->set($cacheKey, $response['data']);
        }

        return new QueryBuilder($response['data']);
    }

    /**
     * Make a request to API to fetch UNOR data.
     *
     * @return array Response from the API.
     */
    public function request(): array
    {
        $httpClient = new HttpClient($this->config->getApiBaseUrl());
        $response   = $httpClient->get('/apisiasn/1.0/referensi/ref-unor', [
            'headers' => [
                'Authorization' => 'Bearer ' . $this->getWsoAccessToken(),
                'Auth'          => 'bearer ' . $this->getSsoAccessToken(),
                'Accept'        => 'application/json'
            ]
        ]);

        return $response;
    }

    /**
     * Get SSO access token.
     *
     * @return string SSO access token.
     */
    public function getSsoAccessToken(): string
    {
        return $this->authentication->getSsoAccessToken();
    }

    /**
     * Get WSO access token.
     *
     * @return string WSO access token.
     */
    public function getWsoAccessToken(): string
    {
        return $this->authentication->getWsoAccessToken();
    }
}
