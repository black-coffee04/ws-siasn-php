<?php

namespace SiASN\Sdk\Exceptions;

use Exception;

/**
 * Class SiasnRequestException.
 *
 * Exception yang digunakan untuk menangani kesalahan pada permintaan REST.
 *
 * @package SiASN\Sdk\Exceptions
 */
class SiasnRequestException extends Exception
{
    /**
     * SiasnRequestException constructor.
     *
     * @param string $message Pesan kesalahan.
     * @param int $code Kode kesalahan.
     * @param Exception|null $previous Exception sebelumnya.
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
