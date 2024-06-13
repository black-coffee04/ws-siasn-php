<?php

namespace SiASN\Sdk\Exceptions;

use Exception;
use InvalidArgumentException;

/**
 * Class SiasnCredentialsException.
 *
 * Exception yang digunakan untuk menangani kesalahan pada permintaan REST.
 *
 * @package SiASN\Sdk\Exceptions
 */
class SiasnCredentialsException extends InvalidArgumentException
{
    /**
     * SiasnCredentialsException constructor.
     *
     * @param string $message Pesan kesalahan.
     * @param int $code Kode kesalahan.
     * @param Exception|null $previous Exception sebelumnya.
     */
    public function __construct($key, $code = 0, Exception $previous = null)
    {
        parent::__construct("{$key} tidak ditemukan, pastikan telah diisi", $code, $previous);
    }
}
