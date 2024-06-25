<?php

namespace SiASN\Sdk\Services;

use SiASN\Sdk\Exceptions\SiasnServiceException;

/**
 * Class QueryBuilder
 *
 * Kelas QueryBuilder digunakan untuk membangun dan menyaring data dalam array.
 */
class QueryBuilder
{
    /**
     * @var array $data Data awal yang akan diolah.
     */
    private $data;

    /**
     * @var string|null $attribute Atribut yang digunakan untuk pencarian atau penyaringan.
     */
    private $attribute;

    /**
     * @var string|null $keyword Kata kunci untuk pencarian atau penyaringan.
     */
    private $keyword;

    /**
     * @var array $filteredData Data hasil dari pencarian atau penyaringan.
     */
    private $filteredData;

    /**
     * Constructor untuk QueryBuilder.
     *
     * @param array $data Data awal yang akan diolah.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
        $this->filteredData = $data;
    }

    /**
     * Melakukan pencarian berdasarkan atribut yang ditentukan.
     *
     * @param string $attribute Atribut yang akan dicari.
     * @return $this Instansi QueryBuilder untuk memungkinkan pemanggilan berantai.
     */
    public function search(string $attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * Melakukan penyaringan dengan menggunakan kata kunci tertentu pada atribut yang telah ditentukan.
     *
     * @param string $keyword Kata kunci untuk penyaringan.
     * @return $this Instansi QueryBuilder untuk memungkinkan pemanggilan berantai.
     */
    public function like(string $keyword)
    {
        $this->keyword = $keyword;
        $this->filterByAttribute();
        return $this;
    }

    /**
     * Membatasi jumlah data yang akan ditampilkan.
     *
     * @param int $limit Jumlah maksimal data yang akan ditampilkan.
     * @return $this Instansi QueryBuilder untuk memungkinkan pemanggilan berantai.
     */
    public function limit(int $limit)
    {
        $this->filteredData = array_slice($this->filteredData, 0, $limit);
        return $this;
    }

    /**
     * Mengambil hasil akhir dari proses pencarian dan penyaringan data.
     *
     * @return array Hasil akhir dari proses pencarian dan penyaringan data.
     */
    public function get(): array
    {
        return $this->filteredData;
    }

    /**
     * Melakukan penyaringan data berdasarkan atribut dan kata kunci yang telah ditentukan.
     *
     * @return void
     * @throws SiasnServiceException Jika atribut yang dimaksud tidak ditemukan dalam data.
     */
    private function filterByAttribute()
    {
        $this->filteredData = array_filter($this->data, function($item) {
            if (!isset($item[$this->attribute])) {
                throw new SiasnServiceException("Atribut '{$this->attribute}' tidak ditemukan dalam data.");
            }
            return strpos(strtolower($item[$this->attribute]), strtolower($this->keyword)) !== false;
        });
    }
}
