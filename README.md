# SDK PHP Web Services SIASN

<!-- BADGES_START -->
[![Latest Version][badge-release]][packagist]
[![PHP Version][badge-php]][php]
[![tests](https://github.com/black-coffee04/ws-siasn-php/actions/workflows/tests.yml/badge.svg)](https://github.com/black-coffee04/ws-siasn-php/actions/workflows/tests.yml)
[![Total Downloads][badge-downloads]][downloads]

[badge-release]: https://img.shields.io/packagist/v/black-coffee04/ws-siasn-php.svg?style=flat-square&label=release
[badge-php]: https://img.shields.io/packagist/php-v/black-coffee04/ws-siasn-php.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/black-coffee04/ws-siasn-php.svg?style=flat-square&colorB=mediumvioletred

[packagist]: https://packagist.org/packages/black-coffee04/ws-siasn-php
[php]: https://php.net
[downloads]: https://packagist.org/packages/black-coffee04/ws-siasn-php
<!-- BADGES_END -->

Selamat datang di SiASN Web Service SDK! SDK ini dirancang untuk memudahkan pengembang dalam mengakses layanan SiASN menggunakan PHP. Dengan SDK ini, Anda dapat mengintegrasikan aplikasi Anda dengan layanan SiASN seperti otentikasi, akses data referensi, dan operasi lainnya dengan mudah.

## Daftar Isi

- [Instalasi](#instalasi)
  - [Requirement System](#requirement-system)
  - [Instalasi Menggunakan Composer](#instalasi-menggunakan-composer)
  - [Instalasi Manual](#instalasi-manual)
- [Konfigurasi](#konfigurasi)
  - [Detail Konfigurasi](#detail-konfigurasi)
- [Contoh Penggunaan](#contoh-penggunaan)
- [Dokumentasi API](#dokumentasi-api)
  - [Authentication](#authentication)
  - [Referensi](#referensi)
  - [PNS](#pns)
- [Menjalankan Tes](#menjalankan-tes)
- [Lisensi](#lisensi)

## Instalasi

### Requirement System

Pastikan sistem Anda memenuhi persyaratan berikut sebelum melanjutkan instalasi SDK SiASN:

- PHP versi minimal yang diperlukan adalah **7.4**
- Ekstensi PHP yang diperlukan:
  - `ext-curl`
  - `ext-json`

### Instalasi Menggunakan Composer

Anda dapat menginstal SDK SiASN menggunakan Composer. Pastikan Anda telah menginstal Composer sebelum memulai.

```bash
composer require black-coffee04/ws-siasn-php
```
**atau**

#### Tambahkan Baris Require ke File composer.json Anda

Jika Anda memilih untuk menambahkan dependensi SDK SiASN melalui file `composer.json`, berikut adalah langkah-langkahnya:

1. **Edit File composer.json**

   Buka file `composer.json` proyek Anda.

2. **Tambahkan Baris Require**

   Tambahkan baris berikut di dalam bagian `"require"`:

   ```json
   {
       "require": {
           "black-coffee04/ws-siasn-php": "^1.*"
       }
   }

### Instalasi Manual

Jika Anda memilih untuk melakukan instalasi SDK SiASN secara manual, berikut adalah langkah-langkahnya:

1. **Unduh SDK**

   [Unduh SDK SiASN](https://github.com/black-coffee04/ws-siasn-php/archive/refs/heads/main.zip).

2. **Ekstrak SDK**

   Ekstrak file SDK ke dalam direktori proyek Anda.

3. **Gunakan Autoload**

   Pastikan untuk memasukkan file `autoload.php` SDK ke dalam proyek Anda. File ini akan mengatur autoload sehingga kelas-kelas SDK dapat diakses dengan benar. Berikut adalah contoh cara menggunakan autoload dalam aplikasi PHP:

   ```php
   require_once __DIR__ . '/path/to/ws-siasn-php/autoload.php';

## Konfigurasi

Sebelum menggunakan SDK SiASN, pastikan Anda telah menyiapkan konfigurasi yang diperlukan. Konfigurasi ini biasanya berisi informasi otentikasi dan pengaturan lain yang diperlukan untuk mengakses layanan SiASN. Berikut adalah contoh konfigurasi yang perlu Anda siapkan:

```php
require_once __DIR__ . './../vendor/autoload.php';

use SiASN\Sdk\SiasnClient;

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "username"       => 'usernameAnda',
    "password"       => 'passwordAnda'
];

$siasnClient = new SiasnClient($config);
```

### Detail Konfigurasi

| Parameter       | Nilai Default | Deskripsi                                      |
|-----------------|---------------|------------------------------------------------|
| `consumerKey`   | -             | Kunci konsumen untuk otentikasi aplikasi.      |
| `consumerSecret`| -             | Rahasia konsumen untuk otentikasi aplikasi.    |
| `clientId`      | -             | ID klien untuk otentikasi aplikasi.            |
| `username`      | -             | Nama pengguna untuk otentikasi SSO.            |
| `password`      | -             | Kata sandi untuk otentikasi SSO.               |


## Contoh Penggunaan

Berikut adalah beberapa contoh penggunaan dasar SDK SiASN untuk berbagai operasi seperti pengambilan Access Token dan akses ke data referensi:

### Pengambilan Access Token WSO

Mendapatkan Access Token WSO:

```php
echo $siasnClient->authentication()->getWsoAccessToken() . PHP_EOL;
```

Mendapatkan Access Token SSO:

```php
echo $siasnClient->authentication()->getSsoAccessToken() . PHP_EOL;
```

### Penggunaan Data Referensi

Anda dapat mengakses data referensi seperti data golongan PNS atau data UNOR menggunakan SDK SiASN:

#### Contoh Pengambilan Data UNOR

```php
$cache = true; // Atur menjadi true untuk menyimpan ke cache, cache akan expired dalam 1 jam
echo json_encode($siasnClient->referensi()->unor($cache)) . PHP_EOL;
```
**atau**

Anda dapat melihat contoh penggunaan pada folder [examples](https://github.com/black-coffee04/ws-siasn-php/tree/main/examples).

## Dokumentasi API

### Authentication

Berikut adalah daftar lengkap metode yang tersedia pada resource Authentication:

| Metode                                            | Deskripsi                                             | Kembalian                        |
|---------------------------------------------------|-------------------------------------------------------|-------------------------------|
| `$siasnClient->authentication()->getWsoAccessToken()` | Mengambil access token untuk layanan WSO SiASN.        | `string` Access token         |
| `$siasnClient->authentication()->getSsoAccessToken()` | Mengambil access token untuk layanan SSO SiASN.        | `string` Access token         |

### Referensi

Berikut adalah daftar lengkap metode yang tersedia pada resource Referensi:

| Metode                                      | Deskripsi                                                   | Kembalian                                                     |
|---------------------------------------------|-------------------------------------------------------------|---------------------------------------------------------------|
| `$siasnClient->referensi()->unor($storeCache)` | Mengambil data referensi Unit Organisasi (UNOR). Parameter `$storeCache` dapat diatur ke `true` untuk menyimpan data di cache dengan masa berlaku 3600 detik (default: `false`). | `Array` data referensi Unit Organisasi (UNOR).          |                          |
| `$siasnClient->referensi()->agama()`         | Mengambil data referensi agama.                             | `Array` data referensi agama.                              |
| `$siasnClient->referensi()->alasanHukumanDisiplin()` | Mengambil data referensi alasan hukuman disiplin.        | `Array` data referensi alasan hukuman disiplin.             |
| `$siasnClient->referensi()->cltn()`          | Mengambil data referensi CLTN.                              | `Array` data referensi CLTN.                               |
| `$siasnClient->referensi()->dokumen()`       | Mengambil data referensi dokumen.                           | `Array` data referensi dokumen.                            |
| `$siasnClient->referensi()->eselon()`        | Mengambil data referensi eselon.                            | `Array` data referensi eselon.                             |
| `$siasnClient->referensi()->golonganPns()`   | Mengambil data referensi golongan PNS.                      | `Array` data referensi golongan PNS.                       |
| `$siasnClient->referensi()->instansi()`      | Mengambil data referensi instansi.                          | `Array` data referensi instansi.                           |
| `$siasnClient->referensi()->jabatanFungsional()` | Mengambil data referensi jabatan fungsional.              | `Array` data referensi jabatan fungsional.                 |
| `$siasnClient->referensi()->jabatanPelaksana()` | Mengambil data referensi jabatan pelaksana.              | `Array` data referensi jabatan pelaksana.                  |
| `$siasnClient->referensi()->jenisDiklat()`   | Mengambil data referensi jenis diklat.                      | `Array` data referensi jenis diklat.                       |
| `$siasnClient->referensi()->jenisHukuman()`  | Mengambil data referensi jenis hukuman.                     | `Array` data referensi jenis hukuman.                      |
| `$siasnClient->referensi()->jenisJabatan()`  | Mengambil data referensi jenis jabatan.                     | `Array` data referensi jenis jabatan.                      |
| `$siasnClient->referensi()->jenisKursus()`   | Mengambil data referensi jenis kursus.                      | `Array` data referensi jenis kursus.                       |
| `$siasnClient->referensi()->jenisMutasi()`   | Mengambil data referensi jenis mutasi.                      | `Array` data referensi jenis mutasi.                       |
| `$siasnClient->referensi()->jenisPegawai()`  | Mengambil data referensi jenis pegawai.                     | `Array` data referensi jenis pegawai.                      |
| `$siasnClient->referensi()->jenisPemberhentian()` | Mengambil data referensi jenis pemberhentian.            | `Array` data referensi jenis pemberhentian.                |
| `$siasnClient->referensi()->jenisPengadaan()` | Mengambil data referensi jenis pengadaan.                  | `Array` data referensi jenis pengadaan.                    |
| `$siasnClient->referensi()->jenisPensiun()`  | Mengambil data referensi jenis pensiun.                     | `Array` data referensi jenis pensiun.                      |
| `$siasnClient->referensi()->jenisPenugasan()` | Mengambil data referensi jenis penugasan.                  | `Array` data referensi jenis penugasan.                    |
| `$siasnClient->referensi()->jenisRiwayat()`  | Mengambil data referensi jenis riwayat.                     | `Array` data referensi jenis riwayat.                      |
| `$siasnClient->referensi()->kabupaten()`     | Mengambil data referensi kabupaten.                         | `Array` data referensi kabupaten.                          |
| `$siasnClient->referensi()->kawin()`         | Mengambil data referensi kawin.                             | `Array` data referensi kawin.                              |
| `$siasnClient->referensi()->kedudukanHukum()` | Mengambil data referensi kedudukan hukum.                  | `Array` data referensi kedudukan hukum.                    |
| `$siasnClient->referensi()->kelasJabatan()`  | Mengambil data referensi kelas jabatan.                     | `Array` data referensi kelas jabatan.                      |
| `$siasnClient->referensi()->kenaikanPangkat()` | Mengambil data referensi kenaikan pangkat.                | `Array` data referensi kenaikan pangkat.                   |
| `$siasnClient->referensi()->kepanitiaan()`   | Mengambil data referensi kepanitiaan.                       | `Array` data referensi kepanitiaan.                        |
| `$siasnClient->referensi()->koefisien()`     | Mengambil data referensi koefisien.                         | `Array` data referensi koefisien.                          |
| `$siasnClient->referensi()->kompetensi()`    | Mengambil data referensi kompetensi.                        | `Array` data referensi kompetensi.                         |
| `$siasnClient->referensi()->kpkn()`          | Mengambil data referensi KPKN.                               | `Array` data referensi KPKN.                               |
| `$siasnClient->referensi()->kuadranNilai()`  | Mengambil data referensi kuadran nilai.                     | `Array` data referensi kuadran nilai.                      |
| `$siasnClient->referensi()->kursus()`        | Mengambil data referensi kursus.                             | `Array` data referensi kursus.                             |
| `$siasnClient->referensi()->latihanStruktural()` | Mengambil data referensi latihan struktural.              | `Array` data referensi latihan struktural.                 |
| `$siasnClient->referensi()->pendidikan()`    | Mengambil data referensi pendidikan.                         | `Array` data referensi pendidikan.                         |
| `$siasnClient->referensi()->penghargaan()`   | Mengambil data referensi penghargaan.                        | `Array` data referensi penghargaan.                        |
| `$siasnClient->referensi()->perilakuDanKinerja()` | Mengambil data referensi perilaku dan kinerja.          | `Array` data referensi perilaku dan kinerja.               |
| `$siasnClient->referensi()->periodik()`      | Mengambil data referensi periodik.                           | `Array` data referensi periodik.                           |
| `$siasnClient->referensi()->profesi()`       | Mengambil data referensi profesi.                            | `Array` data referensi profesi.                            |
| `$siasnClient->referensi()->rumpunJabatan()` | Mengambil data referensi rumpun jabatan.                    | `Array` data referensi rumpun jabatan.                     |
| `$siasnClient->referensi()->satuanKerja()`   | Mengambil data referensi satuan kerja.                       | `Array` data referensi satuan kerja.                       |
| `$siasnClient->referensi()->subJabatan()`    | Mengambil data referensi sub jabatan.                        | `Array` data referensi sub jabatan.                        |
| `$siasnClient->referensi()->taspen()`        | Mengambil data referensi taspen.                             | `Array` data referensi taspen.                             |

### PNS

Berikut adalah daftar lengkap metode yang tersedia pada resource PNS:

| Metode                                      | Deskripsi                                                   | Parameter                                                      | Kembalian                                                     |
|---------------------------------------------|-------------------------------------------------------------|----------------------------------------------------------------|---------------------------------------------------------------|
| `$siasnClient->pns()->dataUtama($nipAsn)`                   | Mengambil data utama ASN berdasarkan NIP ASN.               | `$nipAsn` (string): NIP ASN                                    | Array data utama ASN.                                         |
| `$siasnClient->pns()->dataPasangan($nipAsn)`                | Mengambil data pasangan ASN berdasarkan NIP ASN.            | `$nipAsn` (string): NIP ASN                                    | Array data pasangan ASN.                                      |
| `$siasnClient->pns()->dataAnak($nipAsn)`                    | Mengambil data anak ASN berdasarkan NIP ASN.                | `$nipAsn` (string): NIP ASN                                    | Array data anak ASN.                                          |
| `$siasnClient->pns()->dataOrangTua($nipAsn)`                | Mengambil data orang tua ASN berdasarkan NIP ASN.           | `$nipAsn` (string): NIP ASN                                    | Array data orang tua ASN.                                     |
| `$siasnClient->pns()->refreshJabatan($pnsOrangId)`          | Memperbarui data jabatan ASN berdasarkan ID orang ASN.      | `$pnsOrangId` (string): ID orang ASN                           | `true` jika berhasil memperbarui, `false` jika tidak.         |
| `$siasnClient->pns()->refreshGolongan($pnsOrangId)`         | Memperbarui data golongan ASN berdasarkan ID orang ASN.     | `$pnsOrangId` (string): ID orang ASN                           | `true` jika berhasil memperbarui, `false` jika tidak.         |
| `$siasnClient->pns()->nilaiIpAsn($nipAsn)`                  | Mengambil nilai IP ASN berdasarkan NIP ASN.                 | `$nipAsn` (string): NIP ASN                                    | Array nilai IP ASN.                                           |
| `$siasnClient->pns()->foto($pnsOrangId)->setFileName($fileName)->outputStream()` | Mengambil foto profil ASN berdasarkan ID orang ASN dan menyimpannya sebagai file dengan nama tertentu. | `$pnsOrangId` (string): ID orang ASN, `$fileName` (string): Nama file | Menghasilkan output stream foto profil ASN.                    |
| `$siasnClient->pns()->foto($pnsOrangId)->setFileName($fileName)->saveTo($path)` | Menyimpan foto profil ASN ke direktori yang ditentukan. | `$pnsOrangId` (string): ID orang ASN, `$path ` (string): direktori | Void.                    |
| `$siasnClient->pns()->updateDataUtama([...])`               | Memperbarui data utama ASN dengan parameter yang diberikan. | `$data` (array): Data yang akan diperbarui pada data utama ASN  | Array.         |

## Menjalankan Tes

Untuk menjalankan tes pada SDK ini, pastikan Anda telah menginstal dependensi dengan Composer dan konfigurasi yang diperlukan seperti yang dijelaskan sebelumnya.

### Langkah-langkah Menjalankan Tes

1. **Persiapkan Lingkungan**

   Pastikan lingkungan pengembangan Anda sudah siap dengan PHP >= 7.4 dan ekstensi yang diperlukan seperti `ext-curl` dan `ext-json`.

2. **Clone Repositori**

   Clone repositori SDK ini ke komputer lokal Anda:

   ```bash
   git clone https://github.com/black-coffee04/ws-siasn-php.git
   cd ws-siasn-php
    ```
3. **Instal Dependensi**

    Instal semua dependensi menggunakan Composer:

    ```bash
   composer install
    ```
4. **Jalankan Test**

    Jalankan perintah untuk menjalankan tes:

    ```bash
   composer test

   composer test:service #Testing service
    ```

## License

[MIT](https://github.com/black-coffee04/ws-siasn-php/blob/main/LICENSE.md)