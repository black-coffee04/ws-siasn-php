<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Cache\Cache;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Interfaces\ServiceInterface;
use SiASN\Sdk\Resources\HttpClient;

class AuthenticationService implements ServiceInterface
{
    /**
     * Prefix for WSO token cache.
     */
    protected const WSO_TOKEN_CACHE_PREFIX = 'wso.token.';

    /**
     * Prefix for SSO token cache.
     */
    protected const SSO_TOKEN_CACHE_PREFIX = 'sso.token.';

    /**
     * @var Config Configuration class instance.
     */
    private $config;

    /**
     * @var Cache Cache class instance.
     */
    protected $cache;

    /**
     * Constructor untuk AuthenticationService.
     *
     * @param Config $config Konfigurasi aplikasi.
     * @param Cache $cache Instance cache.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->cache  = new Cache();
    }

    /**
     * Mendapatkan access token dari WSO.
     *
     * @return string Access token dari WSO.
     * @throws SiasnRequestException Jika terjadi kesalahan dalam permintaan token.
     */
    public function getWsoAccessToken(): string
    {
        $cacheKey = self::WSO_TOKEN_CACHE_PREFIX . $this->config->getConsumerKey();

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        return $this->requestWsoToken();
    }

    /**
     * Mendapatkan access token dari SSO.
     *
     * @return string Access token dari SSO.
     * @throws SiasnRequestException Jika terjadi kesalahan dalam permintaan token.
     */
    public function getSsoAccessToken(): string
    {
        $cacheKey = self::SSO_TOKEN_CACHE_PREFIX . $this->config->getUsername();

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        return $this->requestSsoToken();
    }

    /**
     * Meminta token dari WSO.
     *
     * @return string Token dari WSO.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta token.
     */
    protected function requestWsoToken(): string
    {
        $postOptions = $this->getWsoPostOptions();

        $httpClient = new HttpClient($this->config->getWsoBaseUrl());
        $response   = $httpClient->post('/oauth2/token', $postOptions);

        $this->cacheToken(self::WSO_TOKEN_CACHE_PREFIX . $this->config->getConsumerKey(), $response['access_token']);

        return $response['access_token'];
    }

    /**
     * Meminta token dari SSO.
     *
     * @return string Token dari SSO.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta token.
     */
    protected function requestSsoToken(): string
    {
        $postOptions = $this->getSsoPostOptions();

        $httpClient = new HttpClient($this->config->getSsoBaseUrl());
        $response   = $httpClient->post('/auth/realms/public-siasn/protocol/openid-connect/token', $postOptions);

        $this->cacheToken(self::SSO_TOKEN_CACHE_PREFIX . $this->config->getUsername(), $response['access_token']);

        return $response['access_token'];
    }

    /**
     * Mengatur token ke cache.
     *
     * @param string $cacheKey Key untuk cache.
     * @param string $token Token yang akan disimpan.
     */
    protected function cacheToken(string $cacheKey, string $token): void
    {
        $expiresIn = isset($response['expires_in']) ? $response['expires_in'] - 10 : 3600; 
        $this->cache->set($cacheKey, $token, $expiresIn);
    }

    /**
     * Mendapatkan opsi POST untuk WSO.
     *
     * @return array Opsi POST untuk WSO.
     */
    protected function getWsoPostOptions(): array
    {
        return [
            'auth' => [
                $this->config->getConsumerKey(), 
                $this->config->getConsumerSecret()
            ],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
    }

    /**
     * Mendapatkan opsi POST untuk WSO.
     *
     * @return array Opsi POST untuk WSO.
     */
    protected function getSsoPostOptions(): array
    {
        return [
            'form_params' => [
                'grant_type' => 'password',
                'client_id'  => $this->config->getClientId(),
                'username'   => $this->config->getUsername(),
                'password'   => $this->config->getPassword(),
            ],
            'headers' => [
                'Accept' => 'application/json'
            ]
        ];
    }
}