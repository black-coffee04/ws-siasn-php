<?php

namespace SiASN\Sdk\Exceptions;

/**
 * Class SiasnHttpClientException.
 *
 * Exception untuk menangani kesalahan klien HTTP.
 *
 * @package SiASN\Sdk\Exceptions
 */
class SiasnHttpClientException extends \Exception
{
    /**
     * SiasnCredentialsException constructor.
     *
     * @param string $message Pesan kesalahan.
     * @param int $code Kode kesalahan.
     * @param Exception|null $previous Exception sebelumnya.
     */
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
