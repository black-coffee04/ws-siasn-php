<?php
namespace SiASN\Sdk\Traits;

trait ResponseTransformerTrait
{
    /**
     * Mengubah array response menjadi format standar.
     *
     * @param array  $response
     * @param string $idKeyName Kunci ID yang digunakan untuk mengambil nilai 'id' dari mapData.
     * @return array
     */
    public function transformResponse(array $response, string $idKeyName = ''): array
    {
        $transformedResponse = [
            'success' => $this->determineSuccess($response),
            'message' => $this->generateMessage($response),
            'data'    => $this->extractData($response, $idKeyName),
        ];

        // Hapus properti yang tidak diperlukan dari array asli
        unset($response['mapData'], $response['code'], $response['success']);

        return $transformedResponse;
    }

    /**
     * Menentukan status sukses dari response.
     *
     * @param array $response
     * @return bool
     */
    private function determineSuccess(array $response): bool
    {
        return isset($response['success'])
            ? (is_int($response['success']) ? (bool) $response['success'] : $response['success'])
            : (isset($response['code']) ? (bool) $response['code'] : false);
    }

    /**
     * Menghasilkan pesan dari response.
     *
     * @param array $response
     * @return string
     */
    private function generateMessage(array $response): string
    {
        return !empty($response['mapData']) && is_string($response['mapData'])
            ? $response['message'] . ', ' . $response['mapData']
            : $response['message'] ?? '';
    }

    /**
     * Mengekstrak data dari response.
     *
     * @param array $response
     * @param string $idKeyName Kunci ID yang digunakan untuk mengambil nilai 'id'.
     * @return array
     */
    private function extractData(array $response, string $idKeyName): array
    {
        if (!empty($response['mapData']) && is_array($response['mapData'])) {
            return ['id' => $response['mapData'][$idKeyName] ?? null];
        }

        return $response['data'] ?? [];
    }
}
