<?php

namespace SiASN\Sdk;

/**
 * Class Config
 *
 * Class ini bertanggung jawab untuk menyimpan konfigurasi yang diperlukan
 * untuk mengakses layanan SIASN.
 */
class Config
{
    /**
     * URL SSO Base 
     *
     * @var string
     */
    private $ssoBaseUrl = 'https://sso-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token';

    /**
     * URL WSO Base 
     *
     * @var string
     */
    private $wsoBaseUrl = 'https://apimws.bkn.go.id/oauth2/token';

    /**
     * URL Api Base 
     *
     * @var string
     */
    private $apiBaseUrl = 'https://apimws.bkn.go.id:8243/apisiasn/1.0';

    /**
     * Consumer Key
     *
     * @var string
     */
    private $consumerKey;

    /**
     * Consumer Secret
     *
     * @var string
     */
    private $consumerSecret;

    /**
     * Client ID
     *
     * @var string
     */
    private $clientId;

    /**
     * Username
     *
     * @var string
     */
    private $username;

    /**
     * Password
     *
     * @var string
     */
    private $password;

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
     */
    public function __construct(array $config = [])
    {
        $this->consumerKey    = $config['consumerKey'] ?? '';
        $this->consumerSecret = $config['consumerSecret'] ?? '';
        $this->clientId       = $config['clientId'] ?? '';
        $this->username       = $config['username'] ?? '';
        $this->password       = $config['password'] ?? '';
    }

    /**
     * Mendapatkan URL SSO Base 
     *
     * @return string URL SSO Base 
     */
    public function getSsoBaseUrl()
    {
        return $this->ssoBaseUrl;
    }

    /**
     * Mendapatkan URL Api Base 
     *
     * @return string URL Api Base 
     */
    public function getApiBaseUrl()
    {
        return $this->apiBaseUrl;
    }

    /**
     * Mendapatkan URL WSO Base 
     *
     * @return string URL WSO Base 
     */
    public function getWsoBaseUrl()
    {
        return $this->wsoBaseUrl;
    }

    /**
     * Mendapatkan Consumer Key
     *
     * @return string Consumer Key
     */
    public function getConsumerKey()
    {
        return $this->consumerKey;
    }

    /**
     * Mendapatkan Consumer Secret
     *
     * @return string Consumer Secret
     */
    public function getConsumerSecret()
    {
        return $this->consumerSecret;
    }

    /**
     * Mendapatkan Client ID
     *
     * @return string Client ID
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Mendapatkan Username
     *
     * @return string Username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Mendapatkan Password
     *
     * @return string Password
     */
    public function getPassword()
    {
        return $this->password;
    }
}
