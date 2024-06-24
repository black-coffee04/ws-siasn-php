<?php

namespace SiASN\Sdk\Interfaces;

/**
 * Interface ServiceInterface
 * 
 * Interface untuk mendefinisikan metode-metode yang dibutuhkan untuk mengakses token dari WSO dan SSO.
 */
interface ServiceInterface
{
    /**
     * Mendapatkan access token dari WSO.
     *
     * @return string Access token dari WSO.
     */
    public function getWsoAccessToken(): string;

    /**
     * Mendapatkan access token dari SSO.
     *
     * @return string Access token dari SSO.
     */
    public function getSsoAccessToken(): string;
}
