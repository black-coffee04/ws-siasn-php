<?php
use PHPUnit\Framework\TestCase;
use SiASN\Sdk\RestRequest;
use SiASN\Sdk\Exceptions\SiasnRequestException;

class RestRequestTest extends TestCase
{
    public function testGetRequest()
    {
        $config = [
            'url' => 'https://training-apimws.bkn.go.id:8243/api/1.0',
            'headers' => [
                'Authorization: Bearer testtoken'
            ]
        ];

        $restRequest = $this->getMockBuilder(RestRequest::class)
            ->onlyMethods(['executeRequest'])
            ->getMock();

        $restRequest->expects($this->once())
            ->method('executeRequest')
            ->with($config);

        $response = $restRequest->get($config);
        $this->assertInstanceOf(RestRequest::class, $response);
    }

    public function testPostRequest()
    {
        $config = [
            'url' => 'https://training-apimws.bkn.go.id:8243/api/1.0',
            'headers' => [
                'Authorization: Bearer testtoken'
            ]
        ];
        $data = [
            'key1' => 'value1',
            'key2' => 'value2'
        ];

        $restRequest = $this->getMockBuilder(RestRequest::class)
            ->onlyMethods(['executeRequest'])
            ->getMock();

        $restRequest->expects($this->once())
            ->method('executeRequest')
            ->with($config, $data);

        $response = $restRequest->post($config, $data);
        $this->assertInstanceOf(RestRequest::class, $response);
    }

    public function testGetBody()
    {
        $restRequest = new RestRequest();

        $reflection = new ReflectionClass($restRequest);
        $property = $reflection->getProperty('body');
        $property->setAccessible(true);
        $property->setValue($restRequest, json_encode(['key' => 'value']));

        $body = $restRequest->getBody();
        $this->assertIsArray($body);
        $this->assertArrayHasKey('key', $body);
        $this->assertEquals('value', $body['key']);
    }

    public function testGetContent()
    {
        $restRequest = new RestRequest();

        $reflection = new ReflectionClass($restRequest);
        $property = $reflection->getProperty('body');
        $property->setAccessible(true);
        $property->setValue($restRequest, 'test content');

        $content = $restRequest->getContent();
        $this->assertEquals('test content', $content);
    }

    public function testGetHeader()
    {
        $restRequest = new RestRequest();

        $reflection = new ReflectionClass($restRequest);
        $property = $reflection->getProperty('headers');
        $property->setAccessible(true);
        $property->setValue($restRequest, ['Content-Type' => 'application/json']);

        $header = $restRequest->getHeader('Content-Type');
        $this->assertEquals('application/json', $header);
    }

    public function testGetHeaders()
    {
        $restRequest = new RestRequest();

        $reflection = new ReflectionClass($restRequest);
        $property = $reflection->getProperty('headers');
        $property->setAccessible(true);
        $property->setValue($restRequest, ['Content-Type' => 'application/json']);

        $headers = $restRequest->getHeaders();
        $this->assertIsArray($headers);
        $this->assertArrayHasKey('Content-Type', $headers);
        $this->assertEquals('application/json', $headers['Content-Type']);
    }

    public function testValidateConfigThrowsException()
    {
        $this->expectException(InvalidArgumentException::class);

        $restRequest = new RestRequest();
        $restRequest->get([]);
    }

    public function testHandleCurlErrorThrowsException()
    {
        $this->expectException(SiasnRequestException::class);

        $restRequest = new RestRequest();
        $url = 'https://www.invalid-url-for-testing.com';

        $restRequest->get(['url' => $url])->getBody();
    }
}
