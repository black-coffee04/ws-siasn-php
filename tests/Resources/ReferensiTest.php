<?php

namespace SiASN\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Resources\Referensi;
use SiASN\Sdk\Config;
use SiASN\Sdk\Cache;

class ReferensiTest extends TestCase
{
    /** @var Referensi */
    private $referensi;

    /** @var Config */
    private $config;

    /** @var Cache */
    private $cache;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->config = $this->createMock(Config::class);
        $this->cache  = $this->createMock(Cache::class);
        
        $this->config->method('getClientId')->willReturn('testClientId');
        $this->config->method('getUsername')->willReturn('testUsername');
        $this->config->method('getPassword')->willReturn('testPassword');
        $this->config->method('getSsoBaseUrl')->willReturn('https://example.com/auth/token');
        
        // Create instance of Referensi with mocked Config and actual Cache
        $this->referensi = new Referensi($this->config);
        $this->referensi->setCache($this->cache);
    }

    public function testGetUnorFromCache()
    {
        $cacheKey      = 'ref.unor.testClientId';
        $expectedData  = ['unit_1', 'unit_2'];

        $this->cache->method('has')
            ->with($cacheKey)
            ->willReturn(true);

        $this->cache->method('get')
            ->with($cacheKey)
            ->willReturn($expectedData);

        $data = $this->referensi->getUnor(true);

        $this->assertEquals($expectedData, $data);
    }
}
