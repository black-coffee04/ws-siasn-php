<?php

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Services\PnsService;
use SiASN\Sdk\Services\AuthenticationService;
use SiASN\Sdk\Config\Config;

class PnsServiceTest extends TestCase
{
    public function testDataUtamaShouldReturnExpectedArray()
    {
        // 1. Mock AuthenticationService
        $auth = $this->createMock(AuthenticationService::class);
        $auth->method('getWsoAccessToken')->willReturn('mock-wso-token');
        $auth->method('getSsoAccessToken')->willReturn('mock-sso-token');

        // 2. Mock Config
        $config = $this->createMock(Config::class);
        $config->method('getApiBaseUrl')->willReturn('https://mock-api.local');

        // 3. Mock sendRequest() method inside PnsService
        $mockedResponse = [
            'data' => [
                'id' => 'pns-123',
                'nama' => 'Budi',
                'nip' => '1234567890'
            ]
        ];

        $pns = $this->getMockBuilder(PnsService::class)
            ->setConstructorArgs([$auth, $config])
            ->onlyMethods(['sendRequest'])
            ->getMock();

        $pns->method('sendRequest')
            ->with('apisiasn/1.0/pns/data-utama/1234567890')
            ->willReturn($mockedResponse);

        // 4. Jalankan dan verifikasi
        $result = $pns->dataUtama('1234567890');

        $this->assertIsArray($result);
        $this->assertEquals('Budi', $result['data']['nama']);
        $this->assertEquals('1234567890', $result['data']['nip']);
    }
}
