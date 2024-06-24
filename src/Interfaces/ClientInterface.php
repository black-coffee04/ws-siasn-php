<?php

namespace SiASN\Sdk\Interfaces;

/**
 * Interface ClientInterface
 *
 * Interface untuk klien HTTP yang menyediakan metode-metode dasar untuk berinteraksi dengan REST API.
 *
 * @package SiASN\Sdk\Interfaces
 */
interface ClientInterface
{
    /**
     * Mengirim permintaan HTTP dengan metode GET ke endpoint yang ditentukan.
     *
     * @param string $endPoint Endpoint yang akan diakses.
     * @param array $options Opsi tambahan untuk permintaan (opsional).
     * @return mixed Hasil respons dari permintaan.
     */
    public function get(string $endPoint, array $options = []);

    /**
     * Mengirim permintaan HTTP dengan metode POST ke endpoint yang ditentukan.
     *
     * @param string $endPoint Endpoint yang akan diakses.
     * @param array $options Opsi tambahan untuk permintaan (opsional).
     * @return mixed Hasil respons dari permintaan.
     */
    public function post(string $endPoint, array $options = []);

    /**
     * Mengirim permintaan HTTP dengan metode PUT ke endpoint yang ditentukan.
     *
     * @param string $endPoint Endpoint yang akan diakses.
     * @param array $options Opsi tambahan untuk permintaan (opsional).
     * @return mixed Hasil respons dari permintaan.
     */
    public function put(string $endPoint, array $options = []);

    /**
     * Mengirim permintaan HTTP dengan metode DELETE ke endpoint yang ditentukan.
     *
     * @param string $endPoint Endpoint yang akan diakses.
     * @param array $options Opsi tambahan untuk permintaan (opsional).
     * @return mixed Hasil respons dari permintaan.
     */
    public function delete(string $endPoint, array $options = []);
}
