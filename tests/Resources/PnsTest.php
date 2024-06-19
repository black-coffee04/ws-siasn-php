<?php

use PHPUnit\Framework\TestCase;
use Mockery as m;
use SiASN\Sdk\Config;
use SiASN\Sdk\Exceptions\SiasnRequestException;
use SiASN\Sdk\Resources\Pns;

class PnsTest extends TestCase
{
    protected $configMock;
    protected $pns;

    protected function setUp(): void
    {
        $this->configMock = m::mock(Config::class);
        $this->configMock->shouldReceive('getApiBaseUrl')->andReturn('https://training-apimws.bkn.go.id:8243/api/1.0');

        $this->pns = m::mock(Pns::class, [$this->configMock])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();
        
        $this->pns->shouldReceive('getSsoAccessToken')->andReturn('mock_sso_token');
        $this->pns->shouldReceive('getWsoAccessToken')->andReturn('mock_wso_token');
    }

    protected function tearDown(): void
    {
        m::close();
    }

    public function testGetDataUtama()
    {
        $nip = '123456789';
        $expectedData = ['nama' => 'Contoh Nama', 'nip' => $nip];

        $this->pns->shouldReceive('fetchDataFromApi')
            ->with('/pns/data-utama/', $nip)
            ->andReturn($expectedData);

        $actualData = $this->pns->dataUtama($nip);
        $this->assertEquals($expectedData, $actualData);
    }

    public function testGetDataPasangan()
    {
        $nip = '123456789';
        $expectedData = ['nama' => 'Nama Pasangan'];

        $this->pns->shouldReceive('fetchDataFromApi')
            ->with('/pns/data-pasangan/', $nip)
            ->andReturn($expectedData);

        $actualData = $this->pns->dataPasangan($nip);
        $this->assertEquals($expectedData, $actualData);
    }

    public function testGetDataAnak()
    {
        $nip = '123456789';
        $expectedData = [['nama' => 'Anak 1'], ['nama' => 'Anak 2']];

        $this->pns->shouldReceive('fetchDataFromApi')
            ->with('/pns/data-anak/', $nip)
            ->andReturn($expectedData);

        $actualData = $this->pns->dataAnak($nip);
        $this->assertEquals($expectedData, $actualData);
    }

    public function testGetDataOrangTua()
    {
        $nip = '123456789';
        $expectedData = [['nama' => 'Orang Tua 1'], ['nama' => 'Orang Tua 2']];

        $this->pns->shouldReceive('fetchDataFromApi')
            ->with('/pns/data-ortu/', $nip)
            ->andReturn($expectedData);

        $actualData = $this->pns->dataOrangTua($nip);
        $this->assertEquals($expectedData, $actualData);
    }

    public function testRefreshJabatan()
    {
        $pnsOrangId = '123456789';

        $this->pns->shouldReceive('refreshData')
            ->with('/pns/data-utama-jabatansync?pns_orang_id=', $pnsOrangId)
            ->andReturn(true);

        $actualResult = $this->pns->refreshJabatan($pnsOrangId);
        $this->assertTrue($actualResult);
    }

    public function testRefreshGolongan()
    {
        $pnsOrangId = '123456789';

        $this->pns->shouldReceive('refreshData')
            ->with('/pns/data-utama-golongansync?pns_orang_id=', $pnsOrangId)
            ->andReturn(true);

        $actualResult = $this->pns->refreshGolongan($pnsOrangId);
        $this->assertTrue($actualResult);
    }

    public function testNlaiIpAsn()
    {
        $nip = '123456789';
        $expectedData = ['nilai' => 95];

        $this->pns->shouldReceive('fetchDataFromApi')
                  ->with('/pns/nilaiipasn/', $nip . '?nipBaru=' . $nip)
                  ->andReturn($expectedData);

        $actualData = $this->pns->nilaiIpAsn($nip);
        $this->assertEquals($expectedData, $actualData);
    }

    public function testValidateNipThrowsException()
    {
        $this->expectException(SiasnRequestException::class);
        $this->expectExceptionMessage('Nomor Induk Pegawai (NIP) harus diisi');
        
        $this->pns->dataUtama('');
    }

    public function testFetchDataThrowsExceptionForInvalidResponse()
    {
        $nip = '123456789';

        $responseMock = m::mock('GuzzleHttp\Psr7\Response');
        $responseMock->shouldReceive('getBody')->andReturn(['invalid' => 'response']);

        $this->pns->shouldReceive('get')
                ->andReturn($responseMock);

        $this->expectException(SiasnRequestException::class);
        $this->expectExceptionMessage('Gagal mengambil data dari API.');

        $reflection = new ReflectionClass($this->pns);
        $method = $reflection->getMethod('fetchDataFromApi');
        $method->setAccessible(true);
        $method->invokeArgs($this->pns, ['/pns/data-utama/', $nip]);
    }

    public function testRefreshJabatanThrowsExceptionForInvalidResponse()
    {
        $nip = '123456789';

        $responseMock = m::mock('GuzzleHttp\Psr7\Response');
        $responseMock->shouldReceive('getBody')->andReturn([
            'Error'   => false, 
            'Message' => 'Deskripsi Error'
        ]);
        
        $this->pns->shouldReceive('get')
                  ->andReturn($responseMock);

        $this->expectException(SiasnRequestException::class);
        $this->expectExceptionMessage('Gagal merefresh data: Deskripsi Error');

        $reflection = new ReflectionClass($this->pns);
        $method = $reflection->getMethod('refreshData');
        $method->setAccessible(true);
        $method->invokeArgs($this->pns, ['/pns/data-utama-golongansync?pns_orang_id=', $nip]);
    }
}
