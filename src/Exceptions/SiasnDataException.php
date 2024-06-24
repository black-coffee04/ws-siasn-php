<?php

namespace SiASN\Sdk\Exceptions;

use Exception;

/**
 * Class SiasnDataException.
 *
 * Exception yang digunakan untuk menangani kesalahan pada permintaan data.
 *
 * @package SiASN\Sdk\Exceptions
 */
class SiasnDataException extends Exception
{
    /**
     * SiasnDataException constructor.
     *
     * @param string $message Pesan kesalahan.
     * @param int $code Kode kesalahan.
     * @param Exception|null $previous Exception sebelumnya.
     */
    public function __construct($key, $code = 0, Exception $previous = null)
    {
        parent::__construct("Data {$key} tidak ditemukan", $code, $previous);
    }
}