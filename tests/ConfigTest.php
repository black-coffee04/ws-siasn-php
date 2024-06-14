<?php

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\SiasnCredentialsException;

class ConfigTest extends TestCase
{
    public function testValidConfig()
    {
        $configArray = [
            'consumerKey'    => 'testConsumerKey',
            'consumerSecret' => 'testConsumerSecret',
            'clientId'       => 'testClientId',
            'username'       => 'testUsername',
            'password'       => 'testPassword',
        ];

        $config = new Config($configArray);

        $this->assertEquals('testConsumerKey', $config->getConsumerKey());
        $this->assertEquals('testConsumerSecret', $config->getConsumerSecret());
        $this->assertEquals('testClientId', $config->getClientId());
        $this->assertEquals('testUsername', $config->getUsername());
        $this->assertEquals('testPassword', $config->getPassword());
    }

    public function testInvalidConfig()
    {
        $this->expectException(SiasnCredentialsException::class);

        $configArray = [
            'consumerKey'    => 'testConsumerKey',
            'consumerSecret' => 'testConsumerSecret',
            'clientId'       => 'testClientId',
            'password'       => 'testPassword',
        ];

        $config = new Config($configArray);
    }

    public function testGetSsoBaseUrl()
    {
        $configArray = [
            'consumerKey'    => 'testConsumerKey',
            'consumerSecret' => 'testConsumerSecret',
            'clientId'       => 'testClientId',
            'username'       => 'testUsername',
            'password'       => 'testPassword',
        ];

        $config = new Config($configArray);

        $this->assertEquals('https://sso-siasn.bkn.go.id/auth/realms/public-siasn/protocol/openid-connect/token', $config->getSsoBaseUrl());
    }

    public function testGetApiBaseUrl()
    {
        $configArray = [
            'consumerKey'    => 'testConsumerKey',
            'consumerSecret' => 'testConsumerSecret',
            'clientId'       => 'testClientId',
            'username'       => 'testUsername',
            'password'       => 'testPassword',
        ];

        $config = new Config($configArray);

        $this->assertEquals('https://apimws.bkn.go.id:8243/apisiasn/1.0', $config->getApiBaseUrl());
    }

    public function testGetWsoBaseUrl()
    {
        $configArray = [
            'consumerKey'    => 'testConsumerKey',
            'consumerSecret' => 'testConsumerSecret',
            'clientId'       => 'testClientId',
            'username'       => 'testUsername',
            'password'       => 'testPassword',
        ];

        $config = new Config($configArray);

        $this->assertEquals('https://apimws.bkn.go.id/oauth2/token', $config->getWsoBaseUrl());
    }
}
