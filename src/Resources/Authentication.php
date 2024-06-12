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
     * @param Config $config Objek konfigurasi.
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
        $data = ['grant_type' => 'client_credentials'];
        $postOptions = $this->getWsoPostOptions();

        $response = $this->post($postOptions, $data);
        $decodedResponse = $this->decodeResponse($response);

        $this->handleResponseError($response, $decodedResponse);
        $this->cacheToken('wso.token.' . $postOptions['username'], $decodedResponse);

        return $decodedResponse['access_token'];
    }

    /**
     * Mengambil token dari cache atau meminta token jika tidak ada di cache.
     *
     * @return string Token dari cache atau dari WSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function getWsoAccessToken(): string
    {
        $cacheKey = 'wso.token.' . $this->config->getConsumerKey();

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
            'grant_type' => 'password',
            'client_id'  => $this->config->getClientId(),
            'username'   => $this->config->getUsername(),
            'password'   => $this->config->getPassword(),
        ];
        $postOptions = $this->getSsoPostOptions();

        $response = $this->post($postOptions, $data);
        $decodedResponse = $this->decodeResponse($response);

        $this->handleResponseError($response, $decodedResponse);
        $this->cacheToken('sso.token.' . $data['username'], $decodedResponse);

        return $decodedResponse['access_token'];
    }

    /**
     * Mengambil token dari cache atau meminta token jika tidak ada di cache.
     *
     * @return string Token dari cache atau dari SSO.
     * @throws RestRequestException Jika terjadi kesalahan saat meminta token.
     */
    public function getSsoAccessToken(): string
    {
        $cacheKey = 'sso.token.' . $this->config->getUsername();

        if ($this->cache->has($cacheKey)) {
            return $this->cache->get($cacheKey);
        }

        return $this->requestSsoToken();
    }

    /**
     * Menangani error dalam respons HTTP.
     *
     * @param string $response Respon HTTP.
     * @param array $decodedResponse Respon yang telah didekode.
     * @throws RestRequestException Jika terjadi kesalahan dalam respons.
     */
    private function handleResponseError(string $response, array $decodedResponse): void
    {
        if ($response === false) {
            throw new RestRequestException('Gagal melakukan permintaan HTTP.');
        }

        if ($decodedResponse === null) {
            throw new RestRequestException('Gagal mendekode respons JSON.');
        }

        if (!isset($decodedResponse['access_token'])) {
            $errorMessage = $decodedResponse['error'] ?? 'Respon tidak mengandung token akses.';
            throw new RestRequestException($errorMessage);
        }

        if (!isset($decodedResponse['expires_in'])) {
            throw new RestRequestException('Respon tidak mengandung waktu kedaluwarsa.');
        }
    }

    /**
     * Mengatur token ke cache.
     *
     * @param string $cacheKey Key untuk cache.
     * @param array $decodedResponse Respon yang telah didekode.
     */
    private function cacheToken(string $cacheKey, array $decodedResponse): void
    {
        $expiresIn = $decodedResponse['expires_in'] - 10;
        $this->cache->set($cacheKey, $decodedResponse['access_token'], $expiresIn);
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

    /**
     * Mendekode respons JSON.
     *
     * @param string $response Respons JSON.
     * @return array Respons yang telah didekode.
     * @throws RestRequestException Jika gagal mendekode JSON.
     */
    private function decodeResponse(string $response): array
    {
        $decodedResponse = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RestRequestException('Gagal mendekode respons JSON: ' . json_last_error_msg());
        }
        return $decodedResponse;
    }
}
