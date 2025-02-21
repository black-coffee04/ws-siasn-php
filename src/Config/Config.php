<?php

namespace SiASN\Sdk\Config;

use SiASN\Sdk\Exceptions\SiasnCredentialsException;

/**
 * Class Config
 *
 * Menyimpan konfigurasi yang diperlukan untuk mengakses layanan SIASN.
 */
class Config
{
    /** @var string URL SSO Base */
    private $ssoBaseUrl = 'https://sso-siasn.bkn.go.id';

    /** @var string URL WSO Base */
    private $wsoBaseUrl = 'https://apimws.bkn.go.id';

    /** @var string URL API Base */
    private $apiBaseUrl = 'https://apimws.bkn.go.id:8243';

    /** @var string Consumer Key */
    private $consumerKey;

    /** @var string Consumer Secret */
    private $consumerSecret;

    /** @var string Client ID */
    private $clientId;

    /** @var string Access Token */
    private $ssoAccessToken;

    /**
     * Constructor
     *
     * Menginisialisasi konfigurasi dari array yang diberikan.
     *
     * @param array $config Array konfigurasi dengan kunci:
     *                      - consumerKey
     *                      - consumerSecret
     *                      - clientId
     *                      - username
     *                      - password
     * @throws InvalidArgumentException Jika salah satu konfigurasi tidak diatur atau kosong.
     */
    public function __construct(array $config = [])
    {
        $this->consumerKey    = $this->validateConfigValue($config, 'consumerKey');
        $this->consumerSecret = $this->validateConfigValue($config, 'consumerSecret');
        $this->clientId       = $this->validateConfigValue($config, 'clientId');
        $this->ssoAccessToken = $this->validateConfigValue($config, 'ssoAccessToken');
    }

    /**
     * Memvalidasi nilai konfigurasi.
     *
     * @param array $config Array konfigurasi.
     * @param string $key Kunci konfigurasi yang divalidasi.
     * @return string Nilai konfigurasi yang divalidasi.
     * @throws InvalidArgumentException Jika nilai konfigurasi tidak diatur atau kosong.
     */
    private function validateConfigValue(array $config, string $key): string
    {
        if (empty($config[$key])) {
            throw new SiasnCredentialsException($key);
        }
        return $config[$key];
    }

    /**
     * Mendapatkan URL SSO Base
     *
     * @return string URL SSO Base
     */
    public function getSsoBaseUrl(): string
    {
        return $this->ssoBaseUrl;
    }

    /**
     * Mendapatkan URL API Base
     *
     * @return string URL API Base
     */
    public function getApiBaseUrl(): string
    {
        return $this->apiBaseUrl;
    }

    /**
     * Mendapatkan URL WSO Base
     *
     * @return string URL WSO Base
     */
    public function getWsoBaseUrl(): string
    {
        return $this->wsoBaseUrl;
    }

    /**
     * Mendapatkan Consumer Key
     *
     * @return string Consumer Key
     */
    public function getConsumerKey(): string
    {
        return $this->consumerKey;
    }

    /**
     * Mendapatkan Consumer Secret
     *
     * @return string Consumer Secret
     */
    public function getConsumerSecret(): string
    {
        return $this->consumerSecret;
    }

    /**
     * Mendapatkan Client ID
     *
     * @return string Client ID
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * Mendapatkan SSO AccessToken
     *
     * @return string SSO Access Token
     */
    public function getSsoAccessToken(): string
    {
        return $this->ssoAccessToken;
    }
}
