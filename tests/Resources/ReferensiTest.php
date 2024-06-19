<?php

namespace SiASN\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Resources\Referensi;
use SiASN\Sdk\Config;
use SiASN\Sdk\Cache;
use SiASN\Sdk\Exceptions\SiasnRequestException;

class ReferensiTest extends TestCase
{
    /** @var Referensi */
    private $referensi;

    /** @var Config */
    private $config;

    /** @var Cache|\PHPUnit\Framework\MockObject\MockObject */
    private $cache;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = $this->createMock(Config::class);
        $this->cache = $this->createMock(Cache::class);

        $this->config->method('getClientId')->willReturn('testClientId');
        $this->config->method('getApiBaseUrl')->willReturn('https://example.com/api');
        $this->config->method('getUsername')->willReturn('testUsername');
        $this->config->method('getPassword')->willReturn('testPassword');
        $this->config->method('getConsumerKey')->willReturn('testConsumerKey');
        $this->config->method('getConsumerSecret')->willReturn('testConsumerSecret');

        $this->referensi = new Referensi($this->config);
        $this->referensi->setCache($this->cache);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->referensi = null;
        $this->config = null;
        $this->cache = null;
    }

    public function testUnorFromCache()
    {
        $cacheKey = 'ref.unor.testClientId-testConsumerKey';
        $expectedData = ['mocked' => 'unor_data'];

        $this->cache->expects($this->once())
            ->method('has')
            ->with($cacheKey)
            ->willReturn(true);

        $this->cache->expects($this->once())
            ->method('get')
            ->with($cacheKey)
            ->willReturn($expectedData);

        $data = $this->referensi->unor(true);

        $this->assertEquals($expectedData, $data);
    }
}
