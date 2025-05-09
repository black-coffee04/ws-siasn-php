<?php

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Services\AuthenticationService;
use SiASN\Sdk\Config\Config;
use SiASN\Sdk\Cache\Cache;
use SiASN\Sdk\Resources\HttpClient;
use GuzzleHttp\Psr7\Response;

class AuthenticationServiceTest extends TestCase
{
    protected $mockConfig;
    protected $mockCache;
    protected $httpClientMock;
    protected $authService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockConfig = $this->createMock(Config::class);
        $this->mockCache = $this->createMock(Cache::class);
        $this->httpClientMock = $this->createMock(HttpClient::class); // Create mock for HttpClient

        $this->mockConfig->method('getWsoBaseUrl')->willReturn('http://wso.example.com');
        $this->mockConfig->method('getSsoBaseUrl')->willReturn('http://sso.example.com');
        $this->mockConfig->method('getClientId')->willReturn('testClientId');
        $this->mockConfig->method('getSsoAccessToken')->willReturn('ssoAccessTokenTest');
        $this->mockConfig->method('getConsumerKey')->willReturn('testConsumerKey');
        $this->mockConfig->method('getConsumerSecret')->willReturn('testConsumerSecret');

        $this->authService = new AuthenticationService($this->mockConfig);
        $reflection = new ReflectionClass($this->authService);

        $reflectionProperty = $reflection->getProperty('cache');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($this->authService, $this->mockCache);
    }

    public function testGetWsoAccessTokenWithCache()
    {
        $this->mockCache->expects($this->once())
            ->method('has')
            ->with('wso.token.testConsumerKey')
            ->willReturn(true);

        $this->mockCache->expects($this->once())
            ->method('get')
            ->with('wso.token.testConsumerKey')
            ->willReturn('mock_wso_access_token');

        $accessToken = $this->authService->getWsoAccessToken();
        $this->assertEquals('mock_wso_access_token', $accessToken);
    }

    public function testGetSsoAccessTokenWithCache()
    {
        $accessToken = $this->authService->getSsoAccessToken();
        $this->assertEquals('ssoAccessTokenTest', $accessToken);
    }
}
