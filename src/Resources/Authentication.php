<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Cache;
use SiASN\Sdk\Exceptions\RestRequestException;

class Authentication extends \SiASN\Sdk\RestRequest
{
    protected $config;
    protected $cache;

    /**
     * Authentication constructor.
     *
     * @param object $config Objek konfigurasi.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->cache = new Cache();
    }

    /**
     * Meminta token dari WSO.
     *
     * @return string Token dari WSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    private function requestWsoToken(): string
    {
        $data = [
            "grant_type" => 'client_credentials'
        ];

        $postOptions = [
            'url'         => $this->config->getWsoBaseUrl(),
            'headers'     => ['Accept: application/json'],
            'username'    => $this->config->getConsumerKey(),
            'password'    => $this->config->getConsumerSecret(),
            'contentType' => 'urlencoded'
        ];

        $response = $this->post($postOptions, $data);
        $decodedResponse = json_decode($response, true);

        $this->handleResponseError($response, $decodedResponse);

        $expiresIn = $decodedResponse['expires_in'] - 10;

        $cacheKey = 'wso.token.' . $postOptions['username'];
        $this->cache->set($cacheKey, $decodedResponse['access_token'], $expiresIn);

        return $decodedResponse['access_token'];
    }

    /**
     * Mengambil token dari cache atau meminta token jika tidak ada di cache.
     *
     * @return string Token dari cache atau dari WSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function wsoAccessToken(): string
    {
        $username = $this->config->getConsumerKey();
        $cacheKey = 'wso.token.' . $username;

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        return $this->requestWsoToken();
    }

    /**
     * Meminta token dari SSO.
     *
     * @return string Token dari SSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    private function requestSsoToken(): string
    {
        $data = [
            "grant_type" => 'password',
            "client_id"  => $this->config->getClientId(),
            "username"   => $this->config->getUsername(),
            "password"   => $this->config->getPassword(),
        ];

        $postOptions = [
            'url'         => $this->config->getSsoBaseUrl(),
            'headers'     => ['Accept: application/json'],
            'contentType' => 'urlencoded'
        ];

        $response = $this->post($postOptions, $data);
        $decodedResponse = json_decode($response, true);

        $this->handleResponseError($response, $decodedResponse);

        $expiresIn = $decodedResponse['expires_in'] - 10;

        $cacheKey = 'sso.token.' . $data['username'];
        $this->cache->set($cacheKey, $decodedResponse['access_token'], $expiresIn);

        return $decodedResponse['access_token'];
    }

    /**
     * Mengambil token dari cache atau meminta token jika tidak ada di cache.
     *
     * @return string Token dari cache atau dari SSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function ssoAccessToken(): string
    {
        $username = $this->config->getUsername();
        $cacheKey = 'sso.token.' . $username;

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        return $this->requestSsoToken();
    }

    /**
     * Menangani error dalam respons HTTP.
     *
     * @param string $response Respon HTTP.
     * @param mixed $decodedResponse Respon yang telah didekode.
     * @throws RestRequestException Jika terjadi kesalahan dalam respons.
     */
    private function handleResponseError(string $response, $decodedResponse): void
    {
        if ($response === false) {
            throw new RestRequestException('Gagal melakukan permintaan HTTP.');
        }

        if ($decodedResponse === null) {
            throw new RestRequestException('Gagal mendekode respons JSON.');
        }

        if (!isset($decodedResponse['access_token'])) {
            $errorMessage = isset($decodedResponse['error']) ? $decodedResponse['error'] : 'Respon tidak mengandung token akses.';
            throw new RestRequestException($errorMessage);
        }

        if (!isset($decodedResponse['expires_in'])) {
            throw new RestRequestException('Respon tidak mengandung waktu kedaluwarsa.');
        }
    }
}
