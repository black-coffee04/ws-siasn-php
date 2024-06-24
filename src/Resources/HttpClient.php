<?php

namespace SiASN\Sdk\Resources;

use SiASN\Sdk\Interfaces\ClientInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use SiASN\Sdk\Exceptions\SiasnHttpClientException;

/**
 * Class HttpClient
 *
 * Implementasi dari ClientInterface menggunakan GuzzleHttp\Client
 * untuk melakukan permintaan HTTP ke server berdasarkan base URI yang
 * diberikan.
 *
 * @package SiASN\Sdk\Resources
 */
class HttpClient implements ClientInterface
{
    /**
     * @var Client Instance dari Guzzle HTTP Client.
     */
    private $client;

    /**
     * Constructor HttpClient.
     *
     * @param string $baseUri Base URI untuk digunakan sebagai base pada semua permintaan.
     */
    public function __construct(string $baseUri)
    {
        $this->client = new Client(['base_uri' => $baseUri]);
    }

    /**
     * Melakukan permintaan HTTP menggunakan Guzzle HTTP Client.
     *
     * @param string $method Metode HTTP seperti GET, POST, PUT, DELETE, dll.
     * @param string $endPoint End-point atau path yang dituju.
     * @param array $options Opsi tambahan untuk dikirim dalam permintaan.
     * @return mixed Hasil permintaan dalam bentuk array setelah di-decode dari JSON.
     * @throws SiasnHttpClientException Ketika terjadi kesalahan pada permintaan.
     */
    private function request(string $method, string $endPoint, array $options = [])
    {
        try {
            $response = $this->client->request($method, $endPoint, $options);
            return $this->handleResponse($response);
        } catch (RequestException | GuzzleException $e) {
            $this->handleException($e);
        }
    }

    /**
     * Menangani semua jenis exception yang terjadi.
     *
     * @param RequestException|GuzzleException $e Exception yang dilempar saat kesalahan terjadi.
     * @throws SiasnHttpClientException Exception yang dilempar dengan informasi kesalahan.
     */
    private function handleException($e)
    {
        $errorMessage = $e->getMessage();
        $statusCode   = null;
        $response     = $e->getResponse();

        if ($response instanceof ResponseInterface) {
            $statusCode   = $response->getStatusCode();
            $errorMessage = $response->getBody()->getContents();

            if ($this->isNotFoundDataException($statusCode, $errorMessage)) {
                return;
            }
        }

        throw new SiasnHttpClientException($errorMessage, $statusCode);
    }

    /**
     * Memeriksa apakah exception adalah 404 dengan pesan khusus yang dapat diabaikan.
     *
     * @param int $statusCode Status kode HTTP dari response.
     * @param string $errorMessage Pesan error dalam bentuk JSON.
     * @return bool True jika error dapat diabaikan, sebaliknya false.
     */
    private function isNotFoundDataException(int $statusCode, string $errorMessage): bool
    {
        if ($statusCode === 404) {
            $errorData = json_decode($errorMessage, true);
            return isset($errorData['code'], $errorData['data']) &&
                   $errorData['code'] === 0 &&
                   $errorData['data'] === 'Data tidak ditemukan';
        }

        return false;
    }

    /**
     * Melakukan permintaan GET ke server.
     *
     * @param string $endPoint End-point atau path yang dituju.
     * @param array $options Opsi tambahan untuk dikirim dalam permintaan.
     * @return mixed Hasil permintaan dalam bentuk array setelah di-decode dari JSON.
     * @throws SiasnHttpClientException Ketika terjadi kesalahan pada permintaan.
     */
    public function get(string $endPoint = '', array $options = [])
    {
        return $this->request('GET', $endPoint, $options);
    }

    /**
     * Melakukan permintaan POST ke server.
     *
     * @param string $endPoint End-point atau path yang dituju.
     * @param array $options Opsi tambahan untuk dikirim dalam permintaan.
     * @return mixed Hasil permintaan dalam bentuk array setelah di-decode dari JSON.
     * @throws SiasnHttpClientException Ketika terjadi kesalahan pada permintaan.
     */
    public function post(string $endPoint = '', array $options = [])
    {
        return $this->request('POST', $endPoint, $options);
    }

    /**
     * Melakukan permintaan PUT ke server.
     *
     * @param string $endPoint End-point atau path yang dituju.
     * @param array $options Opsi tambahan untuk dikirim dalam permintaan.
     * @return mixed Hasil permintaan dalam bentuk array setelah di-decode dari JSON.
     * @throws SiasnHttpClientException Ketika terjadi kesalahan pada permintaan.
     */
    public function put(string $endPoint = '', array $options = [])
    {
        return $this->request('PUT', $endPoint, $options);
    }

    /**
     * Melakukan permintaan DELETE ke server.
     *
     * @param string $endPoint End-point atau path yang dituju.
     * @param array $options Opsi tambahan untuk dikirim dalam permintaan.
     * @return mixed Hasil permintaan dalam bentuk array setelah di-decode dari JSON.
     * @throws SiasnHttpClientException Ketika terjadi kesalahan pada permintaan.
     */
    public function delete(string $endPoint = '', array $options = [])
    {
        return $this->request('DELETE', $endPoint, $options);
    }

    /**
     * Mengelola respons HTTP yang diterima dari server.
     *
     * @param ResponseInterface $response Objek respons PSR-7 dari server.
     * @return mixed Data dari respons.
     */
    private function handleResponse(ResponseInterface $response)
    {
        $contentType = $response->getHeaderLine('Content-Type');

        if (strpos($contentType, 'application/json') !== false) {
            return json_decode($response->getBody()->getContents(), true);
        }

        return $response;
    }
}
