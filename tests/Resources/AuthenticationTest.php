<?php

namespace SiASN\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Resources\Authentication;
use SiASN\Sdk\Config;
use SiASN\Sdk\Cache;
use PHPUnit\Framework\MockObject\MockObject;
use SiASN\Sdk\Exceptions\SiasnRequestException;

class AuthenticationTest extends TestCase
{
    /** @var Authentication */
    private $authentication;

    /** @var MockObject|Config */
    private $config;

    /** @var MockObject|Cache */
    private $cache;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->config = $this->createMock(Config::class);
        $this->cache  = $this->createMock(Cache::class);
        
        $this->config->method('getClientId')->willReturn('testClientId');
        $this->config->method('getUsername')->willReturn('testUsername');
        $this->config->method('getPassword')->willReturn('testPassword');
        $this->config->method('getConsumerKey')->willReturn('testConsumerKey');
        $this->config->method('getConsumerSecret')->willReturn('testConsumerSecret');
        $this->config->method('getSsoBaseUrl')->willReturn('https://example.com/auth/token');
        
        $this->authentication = new Authentication($this->config);
        $this->authentication->setCache($this->cache);
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        
        $this->config = null;
        $this->cache = null;
        $this->authentication = null;
    }

    public function testGetSsoAccessTokenFromCache()
    {
        $cacheKey      = 'sso.token.' . $this->config->getUsername();
        $expectedToken = 'cached_sso_access_token';

        $this->cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);

        $this->cache->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn($expectedToken);

        $token = $this->authentication->getSsoAccessToken();
        $this->assertEquals($expectedToken, $token);
    }

    public function testGetWsoAccessTokenFromCache()
    {
        $cacheKey      = 'wso.token.' . $this->config->getConsumerKey();
        $expectedToken = 'cached_wso_access_token';

        $this->cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);

        $this->cache->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn($expectedToken);

        $token = $this->authentication->getWsoAccessToken();
        $this->assertEquals($expectedToken, $token);
    }
}