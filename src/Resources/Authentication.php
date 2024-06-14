<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Cache;
use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;

class Authentication extends \SiASN\Sdk\RestRequest
{
    protected $config;
    protected $cache;

    private const WSO_TOKEN_CACHE_PREFIX = 'wso.token.';
    private const SSO_TOKEN_CACHE_PREFIX = 'sso.token.';

    /**
     * Authentication constructor.
     *
     * @param Config $config Objek konfigurasi.
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->cache = new Cache();
    }

    /**
     * Set objek cache.
     *
     * @param Cache $cache Objek cache.
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Mengambil token dari cache atau meminta token dari WSO jika tidak ada di cache.
     *
     * @return string Token dari cache atau dari WSO.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function getWsoAccessToken(): string
    {
        $cacheKey = self::WSO_TOKEN_CACHE_PREFIX . $this->config->getConsumerKey();

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $token = $this->requestWsoToken();
        if ($token && $this->cache) {
            $this->cacheToken($cacheKey, $token);
        }

        return $token;
    }

    /**
     * Mengambil token dari cache atau meminta token dari SSO jika tidak ada di cache.
     *
     * @return string Token dari cache atau dari SSO.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function getSsoAccessToken(): string
    {
        $cacheKey = self::SSO_TOKEN_CACHE_PREFIX . $this->config->getUsername();

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        $token = $this->requestSsoToken();
        if ($token && $this->cache) {
            $this->cacheToken($cacheKey, $token);
        }

        return $token;
    }

    /**
     * Meminta token dari WSO.
     *
     * @return string Token dari WSO.
     * @throws SiasnRequestException Jika terjadi kesalahan saat meminta token.
     */
    protected function requestWsoToken(): string
    {
        $data = ['grant_type' => 'client_credentials'];
        $postOptions = $this->getWsoPostOptions();

        $response = $this->post($postOptions, $data)->getBody();

        $this->handleResponseError($response);
        $this->cacheToken(self::WSO_TOKEN_CACHE_PREFIX . $postOptions['username'], $response['access_token']);

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
        $data = [
            'grant_type' => 'password',
            'client_id'  => $this->config->getClientId(),
            'username'   => $this->config->getUsername(),
            'password'   => $this->config->getPassword(),
        ];
        $postOptions = $this->getSsoPostOptions();

        $response = $this->post($postOptions, $data)->getBody();

        $this->handleResponseError($response);
        $this->cacheToken(self::SSO_TOKEN_CACHE_PREFIX . $data['username'], $response['access_token']);

        return $response['access_token'];
    }

    /**
     * Menangani error dalam respons HTTP.
     *
     * @param array|null $response Respon yang telah didekode.
     * @throws SiasnRequestException Jika terjadi kesalahan dalam respons.
     */
    private function handleResponseError(?array $response): void
    {
        if ($response === false) {
            throw new SiasnRequestException('Gagal melakukan permintaan HTTP.');
        }

        if ($response === null) {
            throw new SiasnRequestException('Gagal mendekode respons JSON.');
        }

        if (!isset($response['access_token'])) {
            $errorMessage = $response['error'] ?? 'Respon tidak mengandung token akses.';
            throw new SiasnRequestException($errorMessage);
        }

        if (!isset($response['expires_in'])) {
            throw new SiasnRequestException('Respon tidak mengandung waktu kedaluwarsa.');
        }
    }

    /**
     * Mengatur token ke cache.
     *
     * @param string $cacheKey Key untuk cache.
     * @param string $token Token yang akan disimpan.
     */
    private function cacheToken(string $cacheKey, string $token): void
    {
        $expiresIn = isset($response['expires_in']) ? $response['expires_in'] - 10 : 3600; 
        $this->cache->set($cacheKey, $token, $expiresIn);
    }

    /**
     * Mendapatkan opsi POST untuk WSO.
     *
     * @return array Opsi POST untuk WSO.
     */
    private function getWsoPostOptions(): array
    {
        return [
            'url'         => $this->config->getWsoBaseUrl(),
            'headers'     => ['Accept: application/json'],
            'username'    => $this->config->getConsumerKey(),
            'password'    => $this->config->getConsumerSecret(),
            'contentType' => 'urlencoded'
        ];
    }

    /**
     * Mendapatkan opsi POST untuk SSO.
     *
     * @return array Opsi POST untuk SSO.
     */
    private function getSsoPostOptions(): array
    {
        return [
            'url'         => $this->config->getSsoBaseUrl(),
            'headers'     => ['Accept: application/json'],
            'contentType' => 'urlencoded'
        ];
    }
}
