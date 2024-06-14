<?php

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Cache;

class CacheTest extends TestCase
{
    private $cache;

    protected function setUp(): void
    {
        $this->cache = new Cache(__DIR__ . '/test_cache/');
    }

    protected function tearDown(): void
    {
        array_map('unlink', glob(__DIR__ . "/test_cache/*"));
        rmdir(__DIR__ . '/test_cache');
    }

    public function testSetAndGetCache()
    {
        $key = 'test_key';
        $data = 'test_data';

        $this->assertTrue($this->cache->set($key, $data));
        $this->assertEquals($data, $this->cache->get($key));
    }

    public function testGetCacheExpired()
    {
        $key = 'test_key';
        $data = 'test_data';

        $this->assertTrue($this->cache->set($key, $data, 1));
        sleep(2);
        $this->assertNull($this->cache->get($key));
    }

    public function testDeleteCache()
    {
        $key = 'test_key';
        $data = 'test_data';

        $this->assertTrue($this->cache->set($key, $data));
        $this->assertTrue($this->cache->delete($key));
        $this->assertNull($this->cache->get($key));
    }

    public function testHasCache()
    {
        $key = 'test_key';
        $data = 'test_data';

        $this->assertFalse($this->cache->has($key));
        $this->assertTrue($this->cache->set($key, $data));
        $this->assertTrue($this->cache->has($key));
    }

    public function testHasCacheExpired()
    {
        $key = 'test_key';
        $data = 'test_data';

        $this->assertTrue($this->cache->set($key, $data, 1));
        sleep(2);
        $this->assertFalse($this->cache->has($key));
    }

    public function testSetInvalidData()
    {
        $key = 'test_key';

        $this->assertFalse($this->cache->set($key, null));
        $this->assertFalse($this->cache->set($key, ''));
        $this->assertFalse($this->cache->set($key, []));
    }
}
