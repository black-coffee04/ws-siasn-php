<?php
namespace SiASN\Sdk\Traits;

trait ResponseTransformerTrait
{
    /**
     * Mengubah array response menjadi format standar.
     *
     * @param array|null $response
     * @param string $idKeyName Kunci ID yang digunakan untuk mengambil nilai 'id'.
     * @return array
     */
    public function transformResponse(?array $response, string $idKeyName = 'rwPenghargaanId'): array
    {
        if (is_null($response)) {
            return $this->createErrorResponse('Response Tidak Ada');
        }

        if ($this->hasErrorResponse($response)) {
            return $this->createErrorResponse($response['Message'], $response['Error']);
        }

        return $this->createSuccessResponse($response, $idKeyName);
    }

    /**
     * Membuat respons error standar.
     *
     * @param string $message
     * @param string|null $error
     * @return array
     */
    private function createErrorResponse(string $message, string $error = null): array
    {
        return [
            'success' => $this->determineSuccessFromError($error ?? ''),
            'message' => $message,
            'data'    => [],
        ];
    }

    /**
     * Membuat respons sukses standar.
     *
     * @param array $response
     * @param string $idKeyName
     * @return array
     */
    private function createSuccessResponse(array $response, string $idKeyName): array
    {
        $transformedResponse = [
            'success' => $this->determineSuccess($response),
            'message' => $this->generateMessage($response),
            'data'    => $this->extractData($response, $idKeyName),
        ];

        if (isset($response['count'])) {
            $transformedResponse['total'] = $response['count'];
        }

        // Menghapus kunci yang tidak diperlukan dari response asli
        unset($response['mapData'], $response['code'], $response['success']);

        return $transformedResponse;
    }

    /**
     * Memeriksa apakah response berisi error.
     *
     * @param array $response
     * @return bool
     */
    private function hasErrorResponse(array $response): bool
    {
        return isset($response['Error'], $response['Message']);
    }

    /**
     * Menentukan status sukses dari response berdasarkan kunci 'Error'.
     *
     * @param string $error
     * @return bool
     */
    private function determineSuccessFromError(string $error): bool
    {
        return strtolower($error) === 'false';
    }

    /**
     * Menentukan status sukses dari response.
     *
     * @param array $response
     * @return bool
     */
    private function determineSuccess(array $response): bool
    {
        return isset($response['success']) ? (bool) $response['success'] : (isset($response['code']) ? (bool) $response['code'] : false);
    }

    /**
     * Menghasilkan pesan dari response.
     *
     * @param array $response
     * @return string
     */
    private function generateMessage(array $response): string
    {
        $message        = $response['message'] ?? '';
        $additionalInfo = '';

        if (isset($response['mapData']) && is_string($response['mapData'])) {
            $additionalInfo = $response['mapData'];
        } elseif (isset($response['data']) && is_string($response['data'])) {
            $additionalInfo = $response['data'];
        }

        return !empty($additionalInfo) 
            ? $additionalInfo 
            : $message;
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
        if (!empty($response['mapData'])) {
            
            if (is_array($response['mapData'])) {
                return ['id' => $response['mapData'][$idKeyName] ?? null];
            }

            return [];
        }
    
        return (isset($response['data']) && !is_string($response['data'])) ? $response['data'] : [];
    }
}
