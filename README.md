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
  - [Instalasi Menggunakan Composer](#instalasi-menggunakan-composer)
  - [Instalasi Manual](#instalasi-manual)
- [Konfigurasi](#konfigurasi)
- [Penggunaan Dasar](#penggunaan-dasar)
- [Dokumentasi API](#dokumentasi-api)
- [Contoh Penggunaan Lanjutan](#contoh-penggunaan-lanjutan)
- [Kontribusi](#kontribusi)
- [Lisensi](#lisensi)

## Instalasi

### Instalasi Menggunakan Composer

Anda dapat menginstal SDK SiASN menggunakan Composer. Pastikan Anda telah menginstal Composer sebelum memulai.

```bash
composer require black-coffee04/ws-siasn-php
```
**atau**

#### Tambahkan Baris Require ke File composer.json Anda

Jika Anda memilih untuk menambahkan dependensi SDK SiASN melalui file `composer.json`, berikut adalah langkah-langkahnya:

1. **Edit File composer.json**

   Buka file `composer.json` dari proyek Anda.

2. **Tambahkan Baris Require**

   Tambahkan baris berikut di dalam bagian `"require"`:

   ```json
   {
       "require": {
           "black-coffee04/ws-siasn-php": "^1.0"
       }
   }

### Instalasi Manual

Jika Anda memilih untuk melakukan instalasi SDK SiASN secara manual, berikut adalah langkah-langkahnya:

1. **Unduh SDK**

   Unduh SDK SiASN dari repositori atau sumber yang disediakan.

2. **Ekstrak SDK**

   Ekstrak file SDK ke dalam direktori proyek Anda.

3. **Gunakan Autoload**

   Pastikan untuk memasukkan file `autoload.php` dari SDK ke dalam proyek Anda. File ini akan mengatur autoload sehingga kelas-kelas dari SDK dapat diakses dengan benar. Berikut adalah contoh cara menggunakan autoload dalam aplikasi PHP:

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
## Penggunaan Dasar

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
