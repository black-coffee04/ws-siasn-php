<?php

namespace SiASN\Sdk;

/**
 * Kelas Cache digunakan untuk menyimpan dan mengambil data dari cache.
 */
class Cache
{
    /**
     * @var string Direktori tempat penyimpanan cache.
     */
    private $cacheDir;

    /**
     * Membuat instance Cache.
     *
     * @param string|null $cacheDir Direktori tempat penyimpanan cache (opsional).
     */
    public function __construct(string $cacheDir = null)
    {
        $this->cacheDir = $cacheDir ?: sys_get_temp_dir() . '/siasn_sdk_cache/';
        $this->ensureCacheDirectoryExists();
    }

    /**
     * Menyimpan data ke dalam cache.
     *
     * @param string $key        Kunci untuk data yang akan disimpan.
     * @param mixed  $data       Data yang akan disimpan.
     * @param int    $expiration Waktu kedaluwarsa cache dalam detik (opsional, default: 3600).
     * @return bool              True jika penyimpanan berhasil, false jika gagal.
     */
    public function set(string $key, $data, int $expiration = 3600): bool
    {
        $cacheFile = $this->getCacheFilename($key);
        $cacheData = [
            'expires_at' => time() + $expiration,
            'data'       => $data,
        ];
        return file_put_contents($cacheFile, serialize($cacheData)) !== false;
    }

    /**
     * Mengambil data dari cache.
     *
     * @param string $key Kunci untuk data yang akan diambil.
     * @return mixed|null Data yang berhasil diambil dari cache atau null jika tidak tersedia atau telah kedaluwarsa.
     */
    public function get(string $key)
    {
        $cacheFile = $this->getCacheFilename($key);
        if (file_exists($cacheFile)) {
            $cacheData = unserialize(file_get_contents($cacheFile));
            if ($cacheData['expires_at'] >= time()) {
                return $cacheData['data'];
            }
            $this->delete($key);
        }
        return null;
    }

    /**
     * Menghapus data dari cache.
     *
     * @param string $key Kunci untuk data yang akan dihapus dari cache.
     * @return bool      True jika penghapusan berhasil, false jika tidak.
     */
    public function delete(string $key): bool
    {
        $cacheFile = $this->getCacheFilename($key);
        return file_exists($cacheFile) ? unlink($cacheFile) : false;
    }

    /**
     * Memastikan bahwa direktori cache ada.
     *
     * @return void
     */
    private function ensureCacheDirectoryExists(): void
    {
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }

    /**
     * Memeriksa apakah data tersedia di dalam cache.
     *
     * @param string $key Kunci untuk data yang akan diperiksa.
     * @return bool      True jika data tersedia di cache, false jika tidak.
     */
    public function has(string $key): bool
    {
        $cacheFile = $this->getCacheFilename($key);
        if (file_exists($cacheFile)) {
            $cacheData = unserialize(file_get_contents($cacheFile));
            return $cacheData['expires_at'] >= time();
        }
        return false;
    }

    /**
     * Mendapatkan nama file cache berdasarkan kunci.
     *
     * @param string $key Kunci untuk data cache.
     * @return string    Nama file cache yang sesuai dengan kunci.
     */
    private function getCacheFilename(string $key): string
    {
        return $this->cacheDir . md5($key) . '.cache';
    }
}