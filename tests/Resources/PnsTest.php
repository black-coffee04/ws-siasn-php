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
     * Menguji bahwa metode dataUtama mengembalikan data yang benar.
     */
    public function testDataUtamaReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('ssoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('wsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->dataUtama($nip);

        $this->assertEquals(['some', 'data'], $data);
    }

    /**
     * Menguji bahwa metode dataUtama melempar pengecualian jika NIP kosong.
     */
    public function testDataUtamaThrowsExceptionOnEmptyNip()
    {
        $this->expectException(RestRequestException::class);
        $this->expectExceptionMessage('Nomor Induk Pegawai (NIP) harus diisi');
        $this->pns->dataUtama('');
    }

    /**
     * Menguji bahwa metode dataPasangan mengembalikan data yang benar.
     */
    public function testDataPasanganReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('ssoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('wsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->dataPasangan($nip);

        $this->assertEquals(['some', 'data'], $data);
    }

    /**
     * Menguji bahwa metode dataAnak mengembalikan data yang benar.
     */
    public function testDataAnakReturnsData()
    {
        $nip = '123456';
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://api.example.com');

        $this->pns->shouldReceive('ssoAccessToken')->andReturn('sso_token');
        $this->pns->shouldReceive('wsoAccessToken')->andReturn('wso_token');
        $this->pns->shouldReceive('get')->andReturn(json_encode(['data' => ['some', 'data']]));

        $data = $this->pns->dataAnak($nip);

        $this->assertEquals(['some', 'data'], $data);
    }
}
