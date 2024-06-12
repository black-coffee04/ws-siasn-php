<?php

use PHPUnit\Framework\TestCase;
use SiASN\Sdk\Resources\Pns;
use SiASN\Sdk\Exceptions\RestRequestException;
use Mockery as m;

/**
 * Kelas PNSTest menguji fungsionalitas kelas PNS.
 */
class PnsTest extends TestCase
{
    private $configMock;
    private $pns;

    /**
     * Menyiapkan objek mock dan instance PNS sebelum setiap tes.
     */
    protected function setUp(): void
    {
        $this->configMock = m::mock('SiASN\Sdk\Config');
        $this->pns = m::mock(Pns::class, [$this->configMock])->makePartial();
    }

    /**
     * Membersihkan objek mock setelah setiap tes.
     */
    protected function tearDown(): void
    {
        m::close();
    }

    /**
     * Menguji bahwa metode getDataUtama mengembalikan data yang benar.
     */
    public function testDataUtamaReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('getSsoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('getWsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->getDataUtama($nip);

        $this->assertEquals(['some', 'data'], $data);
    }

    /**
     * Menguji bahwa metode getDataUtama melempar pengecualian jika NIP kosong.
     */
    public function testDataUtamaThrowsExceptionOnEmptyNip()
    {
        $this->expectException(RestRequestException::class);
        $this->expectExceptionMessage('Nomor Induk Pegawai (NIP) harus diisi');
        $this->pns->getDataUtama('');
    }

    /**
     * Menguji bahwa metode getPasangan mengembalikan data yang benar.
     */
    public function testDataPasanganReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('getSsoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('getWsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->getDataPasangan($nip);

        $this->assertEquals(['some', 'data'], $data);
    }

    /**
     * Menguji bahwa metode getDataAnak mengembalikan data yang benar.
     */
    public function testDataAnakReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('getSsoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('getWsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->getDataAnak($nip);

        $this->assertEquals(['some', 'data'], $data);
    }

    public function testDataOrangTuaReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('getSsoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('getWsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->getDataOrangTua($nip);

        $this->assertEquals(['some', 'data'], $data);
    }

    public function testNilaiIpAsnReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('getSsoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('getWsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->getNilaiIpAsn($nip);

        $this->assertEquals(['some', 'data'], $data);
    }
}
