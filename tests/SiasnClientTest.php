<?php

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\SiasnClient;
use SiASN\Sdk\Config;
use SiASN\Sdk\Resources\Authentication;
use SiASN\Sdk\Resources\Referensi;
use SiASN\Sdk\Resources\Pns;

class SiasnClientTest extends TestCase
{
    /** @var SiasnClient */
    private $client;

    protected function setUp(): void
    {
        parent::setUp();
        
        $config = [
            "consumerKey"    => 'dummy_consumer_key',
            "consumerSecret" => 'dummy_consumer_secret',
            "clientId"       => 'dummy_client_id',
            "username"       => 'dummy_username',
            "password"       => 'dummy_consumer_key'
        ];
        $this->client = new SiasnClient($config);
    }

    public function testGetConfig()
    {
        $this->assertInstanceOf(Config::class, $this->client->getConfig());
    }

    public function testAuthentication()
    {
        $auth = $this->client->authentication();
        $this->assertInstanceOf(Authentication::class, $auth);
    }

    public function testReferensi()
    {
        $referensi = $this->client->referensi();
        $this->assertInstanceOf(Referensi::class, $referensi);
    }

    public function testPns()
    {
        $pns = $this->client->pns();
        $this->assertInstanceOf(Pns::class, $pns);
    }
}
