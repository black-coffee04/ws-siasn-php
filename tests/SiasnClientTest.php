<?php

namespace SiASN\Sdk\Tests;

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\SiasnClient;

class SiasnClientTest extends TestCase
{
    /** @var SiasnClient Instance SiasnClient untuk testing. */
    private $siasnClient;

    protected function setUp(): void
    {
        $config = [
            "consumerKey"    => 'dummy_consumer_key',
            "consumerSecret" => 'dummy_consumer_secret',
            "clientId"       => 'dummy_client_id',
            "username"       => 'dummy_username',
            "password"       => 'dummy_consumer_key'
        ];

        $this->siasnClient = new SiasnClient($config);
    }

    public function testGetConfig()
    {
        $config = $this->siasnClient->getConfig();

        $this->assertInstanceOf('SiASN\Sdk\Config', $config);
    }

    public function testReferensi()
    {
        $referensi = $this->siasnClient->referensi();
        $this->assertInstanceOf('SiASN\Sdk\Resources\Referensi', $referensi);
    }

    public function testPns()
    {
        $pns = $this->siasnClient->pns();

        $this->assertInstanceOf('SiASN\Sdk\Resources\PNS', $pns);
    }
}
