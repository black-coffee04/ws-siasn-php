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
- [Dokumentasi API](#dokumentasi-api)
  - [Authentication](#authentication)
  - [Referensi](#referensi)
  - [PNS](#pns)
  - [Jabatan](#jabatan)
  - [Dokumen](#dokumen)
  - [Pemberhentian](#pemberhentiaan)
  - [Pengadaan](#pengadaan)
  - [Penghargaan](#penghargaan)
  - [Kenaikan Pangkat](#kenaikan-pangkat)
  - [Riwayat](#riwayat)
  - [Angka Kredit](#angka-kredit)
  - [CPNS](#cpns)
  - [Diklat](#diklat)
  - [Hukuman Disiplin](#hukuman-disiplin)
  - [Kinerja Periodik](#kinerja-periodik)
  - [Kursus](#kursus)
  - [SKP](#skp)
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
   ```

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
   ```

## Konfigurasi

Sebelum menggunakan SDK SiASN, pastikan Anda telah menyiapkan konfigurasi yang diperlukan. Konfigurasi ini biasanya berisi informasi otentikasi dan pengaturan lain yang diperlukan untuk mengakses layanan SiASN. Berikut adalah contoh konfigurasi yang perlu Anda siapkan:

```php
require_once __DIR__ . './../vendor/autoload.php';

use SiASN\Sdk\SiasnClient;

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
];

$siasnClient = new SiasnClient($config);
```

### Detail Konfigurasi

| Parameter        | Nilai Default | Deskripsi                                   |
| ---------------- | ------------- | ------------------------------------------- |
| `consumerKey`    | -             | Kunci konsumen untuk otentikasi aplikasi.   |
| `consumerSecret` | -             | Rahasia konsumen untuk otentikasi aplikasi. |
| `clientId`       | -             | ID klien untuk otentikasi aplikasi.         |
| `ssoAccessToken` | -             | Token otentikasi SSO.                       |

**atau**

Anda dapat melihat contoh penggunaan pada folder [examples](https://github.com/black-coffee04/ws-siasn-php/tree/main/examples).

## Dokumentasi API

### Authentication

Berikut adalah daftar lengkap metode yang tersedia pada resource Authentication:

| Metode                                                | Deskripsi                                       | Kembalian             |
| ----------------------------------------------------- | ----------------------------------------------- | --------------------- |
| `$siasnClient->authentication()->getWsoAccessToken()` | Mengambil access token untuk layanan WSO SiASN. | `string` Access token |
| `$siasnClient->authentication()->getSsoAccessToken()` | Mengambil access token untuk layanan SSO SiASN. | `string` Access token |

#### Contoh Penggunaan Authentication

Mendapatkan Access Token WSO:

```php
$siasnClient->authentication()->getWsoAccessToken();
```

Mendapatkan Access Token SSO:

```php
$siasnClient->authentication()->getSsoAccessToken();
```

### Referensi

Berikut adalah daftar lengkap metode yang tersedia pada resource Referensi:

## Referensi SIASN Client

Berikut adalah daftar lengkap metode yang tersedia pada resource Referensi:

### Daftar Metode Referensi

| Metode | Deskripsi | Kembalian |
|--------|-----------|-----------|
| `$siasnClient->referensi()->unor($storeCache)` | Mengambil data referensi Unit Organisasi (UNOR). Parameter `$storeCache` dapat diatur ke `true` untuk menyimpan data di cache dengan masa berlaku 3600 detik (default: `false`). | `Array` data referensi Unit Organisasi (UNOR). |
| `$siasnClient->referensi()->agama()` | Mengambil data referensi agama. | `Array` data referensi agama. |
| `$siasnClient->referensi()->alasanHukumanDisiplin()` | Mengambil data referensi alasan hukuman disiplin. | `Array` data referensi alasan hukuman disiplin. |
| `$siasnClient->referensi()->cltn()` | Mengambil data referensi CLTN. | `Array` data referensi CLTN. |
| `$siasnClient->referensi()->dokumen()` | Mengambil data referensi dokumen. | `Array` data referensi dokumen. |
| `$siasnClient->referensi()->eselon()` | Mengambil data referensi eselon. | `Array` data referensi eselon. |
| `$siasnClient->referensi()->golonganPns()` | Mengambil data referensi golongan PNS. | `Array` data referensi golongan PNS. |
| `$siasnClient->referensi()->instansi()` | Mengambil data referensi instansi. | `Array` data referensi instansi. |
| `$siasnClient->referensi()->jabatanFungsional()` | Mengambil data referensi jabatan fungsional. | `Array` data referensi jabatan fungsional. |
| `$siasnClient->referensi()->jabatanPelaksana()` | Mengambil data referensi jabatan pelaksana. | `Array` data referensi jabatan pelaksana. |
| `$siasnClient->referensi()->jenisDiklat()` | Mengambil data referensi jenis diklat. | `Array` data referensi jenis diklat. |
| `$siasnClient->referensi()->jenisHukuman()` | Mengambil data referensi jenis hukuman. | `Array` data referensi jenis hukuman. |
| `$siasnClient->referensi()->jenisJabatan()` | Mengambil data referensi jenis jabatan. | `Array` data referensi jenis jabatan. |
| `$siasnClient->referensi()->jenisKursus()` | Mengambil data referensi jenis kursus. | `Array` data referensi jenis kursus. |
| `$siasnClient->referensi()->jenisMutasi()` | Mengambil data referensi jenis mutasi. | `Array` data referensi jenis mutasi. |
| `$siasnClient->referensi()->jenisPegawai()` | Mengambil data referensi jenis pegawai. | `Array` data referensi jenis pegawai. |
| `$siasnClient->referensi()->jenisPemberhentian()` | Mengambil data referensi jenis pemberhentian. | `Array` data referensi jenis pemberhentian. |
| `$siasnClient->referensi()->jenisPengadaan()` | Mengambil data referensi jenis pengadaan. | `Array` data referensi jenis pengadaan. |
| `$siasnClient->referensi()->jenisPensiun()` | Mengambil data referensi jenis pensiun. | `Array` data referensi jenis pensiun. |
| `$siasnClient->referensi()->jenisPenugasan()` | Mengambil data referensi jenis penugasan. | `Array` data referensi jenis penugasan. |
| `$siasnClient->referensi()->jenisRiwayat()` | Mengambil data referensi jenis riwayat. | `Array` data referensi jenis riwayat. |
| `$siasnClient->referensi()->kabupaten()` | Mengambil data referensi kabupaten. | `Array` data referensi kabupaten. |
| `$siasnClient->referensi()->kawin()` | Mengambil data referensi kawin. | `Array` data referensi kawin. |
| `$siasnClient->referensi()->kedudukanHukum()` | Mengambil data referensi kedudukan hukum. | `Array` data referensi kedudukan hukum. |
| `$siasnClient->referensi()->kelasJabatan()` | Mengambil data referensi kelas jabatan. | `Array` data referensi kelas jabatan. |
| `$siasnClient->referensi()->kenaikanPangkat()` | Mengambil data referensi kenaikan pangkat. | `Array` data referensi kenaikan pangkat. |
| `$siasnClient->referensi()->kepanitiaan()` | Mengambil data referensi kepanitiaan. | `Array` data referensi kepanitiaan. |
| `$siasnClient->referensi()->koefisien()` | Mengambil data referensi koefisien. | `Array` data referensi koefisien. |
| `$siasnClient->referensi()->kompetensi()` | Mengambil data referensi kompetensi. | `Array` data referensi kompetensi. |
| `$siasnClient->referensi()->kpkn()` | Mengambil data referensi KPKN. | `Array` data referensi KPKN. |
| `$siasnClient->referensi()->kuadranNilai()` | Mengambil data referensi kuadran nilai. | `Array` data referensi kuadran nilai. |
| `$siasnClient->referensi()->kursus()` | Mengambil data referensi kursus. | `Array` data referensi kursus. |
| `$siasnClient->referensi()->latihanStruktural()` | Mengambil data referensi latihan struktural. | `Array` data referensi latihan struktural. |
| `$siasnClient->referensi()->pendidikan()` | Mengambil data referensi pendidikan. | `Array` data referensi pendidikan. |
| `$siasnClient->referensi()->penghargaan()` | Mengambil data referensi penghargaan. | `Array` data referensi penghargaan. |
| `$siasnClient->referensi()->perilakuDanKinerja()` | Mengambil data referensi perilaku dan kinerja. | `Array` data referensi perilaku dan kinerja. |
| `$siasnClient->referensi()->periodik()` | Mengambil data referensi periodik. | `Array` data referensi periodik. |
| `$siasnClient->referensi()->profesi()` | Mengambil data referensi profesi. | `Array` data referensi profesi. |
| `$siasnClient->referensi()->rumpunJabatan()` | Mengambil data referensi rumpun jabatan. | `Array` data referensi rumpun jabatan. |
| `$siasnClient->referensi()->satuanKerja()` | Mengambil data referensi satuan kerja. | `Array` data referensi satuan kerja. |
| `$siasnClient->referensi()->subJabatan()` | Mengambil data referensi sub jabatan. | `Array` data referensi sub jabatan. |
| `$siasnClient->referensi()->taspen()` | Mengambil data referensi taspen. | `Array` data referensi taspen. |

#### Contoh Penggunaan Referensi

```php
#Buat ke true apa bila ingin menyimpan dalam cache, cache akan expired dalam 1 jam
$storeCache = true;

#Mengambil referensi data unor
$siasnClient->referensi()->unor($cache)->get();

#Mengambil referensi data agama
$siasnClient->referensi()->agama()->get();
```

#### Metode Tambahan

- **`->search($attributes)`**: Melakukan pencarian berdasarkan atribut yang ditentukan.
- **`->like($keywords)`**: Melakukan penyaringan dengan menggunakan kata kunci tertentu pada atribut yang telah ditentukan.
- **`->limit($limit)`**: Membatasi jumlah data yang ditampilkan berdasarkan nilai `$limit`.
- **`->get()`**: Mengambil hasil akhir dari pencarian dan penyaringan data.

> **Note:** Metode ini hanya berlaku untuk pemanggilan resource referensi saja, dan tidak dapat digunakan pada resource lain

#### Contoh Penggunaan Metode Tambahan

```php
# Mengambil semua data referensi Unit Organisasi (UNOR)
$unorData = $siasnClient->referensi()->unor()->get();

# Mengambil data referensi UNOR dengan batasan 3 data
$unorDataLimited = $siasnClient->referensi()->unor()->limit(3)->get();

# Mengambil data referensi UNOR berdasarkan atribut 'NamaUnor' yang mengandung kata 'puskesmas' dengan batasan 3 data
$filteredUnorData = $siasnClient->referensi()
                                ->agama()
                                ->search('NamaUnor')
                                ->like('puskesmas')
                                ->limit(3)
                                ->get();

# Mengambil semua data referensi UNOR berdasarkan atribut 'NamaUnor' yang mengandung kata 'puskesmas'
$unfilteredUnorData = $siasnClient->referensi()
                                  ->agama()
                                  ->search('NamaUnor')
                                  ->like('puskesmas')
                                  ->get();


```

### PNS

Berikut adalah daftar lengkap metode yang tersedia pada resource PNS:

| Metode                                                                   | Deskripsi                                                                                         | Parameter                                                      | Kembalian                                   |
| ------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------------- | -------------------------------------------------------------- | ------------------------------------------- |
| `$siasnClient->pns()->dataUtama($nipAsn)`                                | Mengambil data utama ASN berdasarkan NIP ASN.                                                     | `$nipAsn` (string): NIP ASN                                    | Array data utama ASN.                       |
| `$siasnClient->pns()->dataPasangan($nipAsn)`                             | Mengambil data pasangan ASN berdasarkan NIP ASN.                                                  | `$nipAsn` (string): NIP ASN                                    | Array data pasangan ASN.                    |
| `$siasnClient->pns()->dataAnak($nipAsn)`                                 | Mengambil data anak ASN berdasarkan NIP ASN.                                                      | `$nipAsn` (string): NIP ASN                                    | Array data anak ASN.                        |
| `$siasnClient->pns()->dataOrangTua($nipAsn)`                             | Mengambil data orang tua ASN berdasarkan NIP ASN.                                                 | `$nipAsn` (string): NIP ASN                                    | Array data orang tua ASN.                   |
| `$siasnClient->pns()->refreshJabatan($nipAsn)`                           | Memperbarui data jabatan ASN berdasarkan NIP ASN.                                                 | `$nipAsn` (string): NIP ASN                                    | Array Respon api.                           |
| `$siasnClient->pns()->refreshGolongan($nipAsn)`                          | Memperbarui data golongan ASN berdasarkan NIP ASN.                                                | `$nipAsn` (string): NIP ASN                                    | Array Respon api.                           |
| `$siasnClient->pns()->nilaiIpAsn($nipAsn)`                               | Mengambil nilai IP ASN berdasarkan NIP ASN.                                                       | `$nipAsn` (string): NIP ASN                                    | Array nilai IP ASN.                         |
| `$siasnClient->pns()->foto($nipAsn)->setName($fileName)->outputStream()` | Mengambil foto profil ASN berdasarkan NIP ASN dan menyimpannya sebagai file dengan nama tertentu. | `$nipAsn` (string): NIP ASN, `$fileName` (string): Nama file   | Menghasilkan output stream foto profil ASN. |
| `$siasnClient->pns()->foto($nipAsn)->setName($fileName)->saveTo($path)`  | Menyimpan foto profil ASN ke direktori yang ditentukan.                                           | `$nipAsn` (string): NIP ASN, `$path` (string): direktori       | String nama file.                           |
| `$siasnClient->pns()->updateDataUtama([...])`                            | Memperbarui data utama ASN dengan parameter yang diberikan.                                       | `$data` (array): Data yang akan diperbarui pada data utama ASN | Array.                                      |

#### Contoh Penggunaan Api PNS

```php
$nipAsn = 'xxxxxxxxxxxxxxxxxxxxx';

#Mengambil data pns berdasarkan NIP
$siasn  = new SiasnClient($config);

#Mengambil Data Utama
$dataUtama = $siasn->pns()->dataUtama($nipAsn);

$data = [
    "alamat"          => "string",
    "email"           => "string",
    "email_gov"       => "string",
    "kabupaten_id"    => "string",
    "karis_karsu"     => "string",
    "kelas_jabatan"   => "string",
    "kpkn_id"         => "string",
    "lokasi_kerja_id" => "string",
    "nomor_bpjs"      => "string",
    "nomor_hp"        => "string",
    "nomor_telpon"    => "string",
    "npwp_nomor"      => "string",
    "npwp_tanggal"    => "string",
    "pns_orang_id"    => "string",
    "tanggal_taspen"  => "string",
    "tapera_nomor"    => "string",
    "taspen_nomor"    => "string",
];
#Update data utama
$update = $siasn->pns()->updateDataUtama($nipAsn);
var_dump($update);
```

### Jabatan

Berikut adalah daftar lengkap metode yang tersedia pada resource **Jabatan**:

| Metode                                                      | Deskripsi                                              | Parameter                                        | Kembalian                    |
|-------------------------------------------------------------|--------------------------------------------------------|--------------------------------------------------|------------------------------|
| `$siasnClient->jabatan()->pns($nipAsn)`                     | Mengambil data jabatan ASN berdasarkan NIP ASN.        | `$nipAsn` (string): NIP ASN                      | Array data jabatan ASN.      |
| `$siasnClient->jabatan()->riwayat($idJabatan)`              | Mengambil data riwayat jabatan berdasarkan ID Jabatan. | `$idJabatan` (string): ID Jabatan                | Array data riwayat jabatan.  |
| `$siasnClient->jabatan()->create($data)->save()`            | Menambahkan data jabatan baru.                         | `$data` (array): Data jabatan                    | Array respons API.           |
| `$siasnClient->jabatan()->createUnorJabatan($data)->save()` | Menambahkan data unit organisasi jabatan.              | `$data` (array): Data jabatan                    | Array respons API.           |
| `$siasnClient->jabatan()->remove($idRiwayatJabatan)`        | Menghapus riwayat jabatan berdasarkan ID.              | `$idRiwayatJabatan` (string): ID Riwayat Jabatan | Array respons API atau `false`. |

#### Metode Tambahan

**`->includeDokumen($file)`**: Menambahkan dokumen/file saat menambahkan data jabatan `$file` bisa menggunakan url/binary file.

#### Contoh Penggunaan Api Jabatan

Mendapatkan data jabatan PNS:

```php
$siasnClient->jabatan()->pns($nipAsn);

#Menambahakan data jabatan beserta dokumennya
$dokumen = "http://url/to/pdf"; #Bisa mengunakan url/binary file
$riwayatJabatanId = $siasnClient->jabatan()
    ->create($data)
    ->includeDokumen($dokumen)
    ->save();
```

### Dokumen

Berikut adalah daftar lengkap metode yang tersedia pada resource dokumen:

| Metode                                                                         | Deskripsi                                                                               | Parameter                                                                                                                                        | Kembalian                                          |
| ------------------------------------------------------------------------------ | --------------------------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------ | -------------------------------------------------- |
| `$siasnClient->dokumen()->download($args)->setName($fileName)->outputStream()` | Mengambil dokumen pada siasn dan langsung menampilkan tanpa disimpan pada local server. | `$args` >(json), (array), (object): respon upload file pada siasn >(string) $dokUri, `$fileName` (string): Nama file                             | Menghasilkan output stream dokumen.                |
| `$siasnClient->dokumen()->download($args)->setName($fileName)->saveTo($path)`  | Menyimpan dokumen ke direktori yang ditentukan.                                         | `$args` >(json), (array), (object): respon upload file pada siasn >(string) $dokUri, `$fileName`(string): Nama file,`$path ` (string): direktori | String $filename.                                  |
| `$siasnClient->dokumen()->upload($idRefDokumen, $file)`                        | Mengunggah dokumen ke SiASN dari file lokal.                                            | `(string) $idRefDokumen`, `(string) $file`: Path file lokal atau URL                                                                             | Data dari respons upload dokumen ke SiASN.         |
| `$siasnClient->dokumen()->uploadRiwayat($idRiwayat, $idRefDokumen, $file)`     | Mengunggah riwayat dokumen ke SiASN dari file lokal.                                    | `(string) $idRiwayat`, `(string) $idRefDokumen`, `(string) $file`: Path file lokal atau URL                                                      | Data dari respons upload riwayat dokumen ke SiASN. |

#### Contoh Penggunaan Api Dokumen

```php
$idRefDokumen = 'string';
$dokumen      = 'path\to\esample.pdf' #Dapat menggunakan binary dokumen/URL dokumen;
$path         = 'path/to/penyimpanan/dokumen';
$fileName     = 'nama file tanpa extensi'; #Nama file tanpa extensi

$response     = $siasnClient->dokumen()->upload($idRefDokumen, $dokumen);
#Save dokumen ke local server
echo $siasnClient->dokumen()->download($response)->setName($fileName)->saveTo($path);

#Tampilkan dokumen tanpa menyimpan
$siasnClient->dokumen()->download($response)->setName($fileName)->outputStream();
```

### Pemberhentiaan

Berikut adalah daftar lengkap metode yang tersedia pada resource pemberhentian:

| Metode                                                            | Deskripsi                                                          | Parameter                                                                      | Kembalian                          |
| ----------------------------------------------------------------- | ------------------------------------------------------------------ | ------------------------------------------------------------------------------ | ---------------------------------- |
| `$siasnClient->pemberhentian()->get($tanggalAwal, $tanggalAkhir)` | Mengambil semua data pensiun instansi berdasarkan periode tanggal. | `$tanggalAwal` (string): Tanggal Awal, `$tanggalAkhir` (string): Tanggal Akhir | Array data pemberhentian Instansi. |

#### Contoh Penggunaan Api Pemberhentian

```php
$tanggalAwal   = '2022-01-01';
$tanggalAkhir  = '2022-12-01';

$daftarPemberhentian   = $siasnClient->pemberhentian()->get($tanggalAwal, $tanggalAkhir);
```

### Pengadaan

Berikut adalah daftar lengkap metode yang tersedia pada resource pengadaan:

| Metode                                   | Deskripsi                                                            | Parameter                       | Kembalian                              |
| ---------------------------------------- | -------------------------------------------------------------------- | ------------------------------- | -------------------------------------- |
| `$siasnClient->pengadaan()->get($tahun)` | Mengambil semua data pengadaan instansi berdasarkan tahun pengadaan. | `$tahun` (string): Tanggal Awal | Array data pengadaan Instansi.         |
| `$siasnClient->pengadaan()->dokumen()`   | Mengambil semua data dokumen pengadaan instansi.                     | -                               | Array data dokumen pengadaan Instansi. |

#### Contoh Penggunaan Api pengadaan

```php
$tahunAnggaran = '2023';

$daftarPengadaan  = $siasnClient->pengadaan()->get($tahunAnggaran);
$dokumenPengadaan = $siasnClient->pengadaan()->dokumen();
```

###

### Penghargaan

Berikut adalah daftar lengkap metode yang tersedia pada resource **Penghargaan**:

| Metode                                                                      | Deskripsi                                                  | Parameter                                                                       | Kembalian                       |
| --------------------------------------------------------------------------- | ---------------------------------------------------------- | ------------------------------------------------------------------------------- | ------------------------------- |
| `$siasnClient->penghargaan()->get($idRiwayatPenghargaan)`                   | Mengambil data riwayat penghargaan berdasarkan ID riwayat. | `$idRiwayatPenghargaan` (string): ID riwayat penghargaan                        | Array data riwayat penghargaan. |
| `$siasnClient->penghargaan()->create($data)->save()`                        | Membuat data riwayat penghargaan tanpa dokumen.            | `$data` (array): Data penghargaan                                               | Array data penghargaan.         |
| `$siasnClient->penghargaan()->create($data)->includeDokumen($file)->save()` | Membuat data riwayat penghargaan dengan dokumen.           | `$data` (array): Data penghargaan, `$file` (string): Path file atau URL dokumen | Array data penghargaan.         |
| `$siasnClient->penghargaan()->remove($idRiwayatPenghargaan)`                | Menghapus data riwayat penghargaan.                        | `$idRiwayatPenghargaan` (string): ID riwayat penghargaan                        | Array respons API atau `false`. |



#### Contoh Penggunaan Api Penghargaan

```php
#minyam data penghargaan
$data = [
    "hargaId"    => "ref_penghargaan",
    "pnsOrangId" => "pnsOrangId",
    "skDate"     => "dd-mm-yyyy",
    "skNomor"    => "string",
    "tahun"      => int,
];

$response = $siasnClient->penghargaan()->create($data)->save();

if (isset($response['success']) && $response['success']) {
    #mengambil data riwayat penghargaan
    $penghargaan = $siasnClient->penghargaan()->get($response['data']['id']);
}

print_r($penghargaan);

#Hapus data riwayat penghargaan
$siasnClient->penghargaan()->remove($idRiwayatPenghargaan)
```

### Kenaikan Pangkat

Berikut adalah daftar lengkap metode yang tersedia pada resource kp:

## Daftar Metode SiASN Client

| Metode                                                                        | Deskripsi                                                              | Parameter                                                                                                                                                                | Kembalian                                                                               |
| ----------------------------------------------------------------------------- | ---------------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------------------------------------------------------ | --------------------------------------------------------------------------------------- |
| `$siasnClient->kp()->get($tanggalAwal, $tanggalAkhir)`                        | Mengambil semua data kenaikan pangkat instansi berdasarkan periode kp. | `$tanggalAwal` (string): Tanggal awal periode KP<br>`$tanggalAkhir` (string): Tanggal akhir periode KP                                                                   | Array data kenaikan pangkat instansi sesuai periode KP.                                 |
| `$siasnClient->kp()->uploadDokumenSk($idUsulan, $nomorSk, $tanggalSk, $file)` | Mengunggah dokumen SK terkait usulan kenaikan pangkat instansi.        | `$idUsulan` (string): ID usulan kenaikan pangkat<br>`$nomorSk` (string): Nomor SK<br>`$tanggalSk` (string): Tanggal SK<br>`$file` (mixed): Dokumen SK yang akan diunggah | Array data kenaikan pangkat instansi yang telah diperbarui dengan dokumen SK terlampir. |

#### Contoh Penggunaan Api Kenaikan Pangkat

```php
$periode = '2022-04-01';

$daftarKp = $siasnClient->kp()->get($periode);
```

### Riwayat

Berikut adalah daftar lengkap metode yang tersedia pada resource riwayat:

| Metode                                           | Deskripsi                                         | Parameter                | Kembalian                            |
| ------------------------------------------------ | ------------------------------------------------- | ------------------------ | ------------------------------------ |
| `$siasnClient->riwayat()->angkaKredit($nip)`     | Mengambil data riwayat angka kredit dari ASN.     | `$nip` (string): NIP Asn | Array data riwayat angka kredit.     |
| `$siasnClient->riwayat()->cltn($nip)`            | Mengambil data riwayat cltn dari ASN.             | `$nip` (string): NIP Asn | Array data riwayat cltn.             |
| `$siasnClient->riwayat()->diklat($nip)`          | Mengambil data riwayat diklat dari ASN.           | `$nip` (string): NIP Asn | Array data riwayat diklat.           |
| `$siasnClient->riwayat()->dp3($nip)`             | Mengambil data riwayat dp3 dari ASN.              | `$nip` (string): NIP Asn | Array data riwayat dp3.              |
| `$siasnClient->riwayat()->golongan($nip)`        | Mengambil data riwayat golongan dari ASN.         | `$nip` (string): NIP Asn | Array data riwayat golongan.         |
| `$siasnClient->riwayat()->hukdis($nip)`          | Mengambil data riwayat hukdis dari ASN.           | `$nip` (string): NIP Asn | Array data riwayat hukdis.           |
| `$siasnClient->riwayat()->jabatan($nip)`         | Mengambil data riwayat jabatan dari ASN.          | `$nip` (string): NIP Asn | Array data riwayat jabatan.          |
| `$siasnClient->riwayat()->kinerjaPeriodik($nip)` | Mengambil data riwayat kinerja periodik dari ASN. | `$nip` (string): NIP Asn | Array data riwayat kinerja periodik. |
| `$siasnClient->riwayat()->kursus($nip)`          | Mengambil data riwayat kursus dari ASN.           | `$nip` (string): NIP Asn | Array data riwayat kursus.           |
| `$siasnClient->riwayat()->masaKerja($nip)`       | Mengambil data riwayat masa kerja dari ASN.       | `$nip` (string): NIP Asn | Array data riwayat masa kerja.       |
| `$siasnClient->riwayat()->pemberhentian($nip)`   | Mengambil data riwayat pemberhentian dari ASN.    | `$nip` (string): NIP Asn | Array data riwayat pemberhentian.    |
| `$siasnClient->riwayat()->pendidikan($nip)`      | Mengambil data riwayat pendidikan dari ASN.       | `$nip` (string): NIP Asn | Array data riwayat pendidikan.       |
| `$siasnClient->riwayat()->penghargaan($nip)`     | Mengambil data riwayat penghargaan dari ASN.      | `$nip` (string): NIP Asn | Array data riwayat penghargaan.      |
| `$siasnClient->riwayat()->pindahInstansi($nip)`  | Mengambil data riwayat pindah instansi dari ASN.  | `$nip` (string): NIP Asn | Array data riwayat pindah instansi.  |
| `$siasnClient->riwayat()->unor($nip)`            | Mengambil data riwayat unor dari ASN.             | `$nip` (string): NIP Asn | Array data riwayat unor.             |
| `$siasnClient->riwayat()->pwk($nip)`             | Mengambil data riwayat pwk dari ASN.              | `$nip` (string): NIP Asn | Array data riwayat pwk.              |
| `$siasnClient->riwayat()->skp($nip)`             | Mengambil data riwayat skp dari ASN.              | `$nip` (string): NIP Asn | Array data riwayat skp.              |
| `$siasnClient->riwayat()->skp22($nip)`           | Mengambil data riwayat skp 2022 dari ASN.         | `$nip` (string): NIP Asn | Array data riwayat skp 2022.         |

#### Contoh Penggunaan Api Riwayat

```php
$nip  = 'xxxxxxxxxxxxxxxxxx';

$riwayatKursus = $siasnClient->riwayat()->kursus($nip);
```

### Angka Kredit

Berikut adalah daftar lengkap metode yang tersedia pada resource **Angka Kredit**:

| Metode                                                                      | Deskripsi                                                   | Parameter                                                                        | Kembalian                        |
| --------------------------------------------------------------------------- | ----------------------------------------------------------- | -------------------------------------------------------------------------------- | -------------------------------- |
| `$siasnClient->angkaKredit()->get($idRiwayatAngkaKredit)`                   | Mengambil data riwayat angka kredit berdasarkan ID riwayat. | `$idRiwayatAngkaKredit` (string): ID riwayat angka kredit                        | Array data riwayat angka kredit. |
| `$siasnClient->angkaKredit()->create($data)->save()`                        | Membuat data riwayat angka kredit tanpa dokumen.            | `$data` (array): Data angka kredit                                               | Array data angka kredit.         |
| `$siasnClient->angkaKredit()->create($data)->includeDokumen($file)->save()` | Membuat data riwayat angka kredit dengan dokumen.           | `$data` (array): Data angka kredit, `$file` (string): Path file atau URL dokumen | Array data angka kredit.         |
| `$siasnClient->angkaKredit()->remove($idRiwayatAngkaKredit)`                | Menghapus data riwayat angka kredit.                        | `$idRiwayatAngkaKredit` (string): ID riwayat angka kredit                        | Array respons API atau `false`.  |



#### Contoh Penggunaan Api Angka Kredit

```php
$data = [
    "bulanMulaiPenailan"   => "string",
    "bulanSelesaiPenailan" => "string",
    "isAngkaKreditPertama" => "string",
    "isIntegrasi"          => "string",
    "isKonversi"           => "string",
    "kreditBaruTotal"      => "string",
    "kreditPenunjangBaru"  => "string",
    "kreditUtamaBaru"      => "string",
    "nomorSk"              => "string",
    "pnsId"                => "pnsId",
    "rwJabatanId"          => "rwJabatanId",
    "tahunMulaiPenailan"   => "yyyy",
    "tahunSelesaiPenailan" => "yyyy",
    "tanggalSk"            => "dd-mm-yyyy",
];

#Menyimpan data angka kredir
$response = $siasnClient->angkaKredit()
    ->create($data)
    ->includeDokumen("https://url_to_file_angka_kredit.pdf")
    ->save();
if (isset($response['success']) && $response['success']) {
    #Mengambil data angka kredir
    $angkaKredit = $siasnClient->angkaKredit()->get($response['data']['id']);
    #Menghapus data angka kredir
    $angkaKredit = $siasnClient->angkaKredit()->remove($response['data']['id']);
}
var_dump($angkaKredit);die();
```

## CPNS

Berikut adalah daftar lengkap metode yang tersedia pada resource cpns:

| Metode                                                                        | Deskripsi                             | Parameter                                                             | Kembalian   |
| ----------------------------------------------------------------------------- | ------------------------------------- | --------------------------------------------------------------------- | ----------- |
| `$siasnClient->cpns()->create($data)->includeDokumen($skCpns, $spmt)->save()` | Menambahkan data cpns dengan dokumen. | `$data` (array): Data CPNS, `$file` (string) Dokumen SK CPNS file/URL | Array data. |
| `$siasnClient->cpns()->create($data)->save()`                                 | Menambahkan data cpns tanpa dokumen.  | `$data` (array): Data CPNS                                            | Array data. |

#### Contoh Penggunaan Api CPNS

```php
$skCpns = "path/to/dokumen.pdf";
$spmt = "path/to/dokumen.pdf";
$siasnClient->cpns()
    ->create($data)
    ->includeDokumen($skCpns, $spmt) #URL FILE atau Path to dokumen
    ->save()
```

## Diklat

Berikut adalah daftar lengkap metode yang tersedia pada resource diklat:

| Metode                                                                 | Deskripsi                                             | Parameter                                                                 | Kembalian                  |
| ---------------------------------------------------------------------- | ----------------------------------------------------- | ------------------------------------------------------------------------- | -------------------------- |
| `$siasnClient->diklat()->get($idRiwayatDiklat)`                        | Mengambil data riwayat diklat berdasarkan id riwayat. | `$idRiwayatDiklat` (string): Id riwayat diklat                            | Array data riwayat diklat. |
| `$siasnClient->diklat()->create($data)->save()`                        | Membuat data riwayat diklat tanpa dokumen.            | `$data` (array): data diklat                                              | Array data diklat.         |
| `$siasnClient->diklat()->create($data)->includeDokumen($file)->save()` | Membuat data riwayat diklat dengan dokumen.           | `$data` (array): data diklat, `$file` (string) path file atau url dokumen | Array data diklat.         |
| `$siasnClient->diklat()->remove($idRiwayatDiklat)`                     | Menghapus data riwayat diklat.                        | `$idRiwayatDiklat` (string) Id riwayat diklat                             | Array.                     |

#### Contoh Penggunaan Api Diklat

```php
$data = [
    "bobot"                  => integer,
    "id"                     => null,
    "institusiPenyelenggara" => "string",
    "jenisKompetensi"        => "string",
    "jumlahJam"              => integer,
    "latihanStrukturalId"    => "string",
    "nomor"                  => "string",
    "pnsOrangId"             => "string",
    "tahun"                  => integer,
    "tanggal"                => "d-m-Y",
    "tanggalSelesai"         => "d-m-Y",
];
$response = $siasnClient->diklat()
    ->create($data)
    ->includeDokumen("file.pdf") //Hapus methods ini jika tidak menggunakan dokumen
    ->save();

$riwayatDiklat = $siasnClient->diklat()->get($response['data']['id']);
print_r($riwayatDiklat);

$riwayatDiklat = $siasnClient->diklat()->remove($response['data']['id']);
```

## Hukuman Disiplin

Berikut adalah daftar lengkap metode yang tersedia pada resource hukuman disiplin:

| Metode                                                                 | Deskripsi                                             | Parameter                                                                 | Kembalian                  |
| ---------------------------------------------------------------------- | ----------------------------------------------------- | ------------------------------------------------------------------------- | -------------------------- |
| `$siasnClient->hukdis()->get($idRiwayatHukdis)`                        | Mengambil data riwayat hukdis berdasarkan id riwayat. | `$idRiwayatHukdis` (string): Id riwayat hukdis                            | Array data riwayat hukdis. |
| `$siasnClient->hukdis()->create($data)->save()`                        | Membuat data riwayat hukdis tanpa dokumen.            | `$data` (array): data hukdis                                              | Array data hukdis.         |
| `$siasnClient->hukdis()->create($data)->includeDokumen($file)->save()` | Membuat data riwayat hukdis dengan dokumen.           | `$data` (array): data hukdis, `$file` (string) path file atau url dokumen | Array data hukdis.         |

#### Contoh Penggunaan Api Hukdis

```php
$data = [
    "akhirHukumanTanggal"       => "string",
    "alasanHukumanDisiplinId"   => "string",
    "golonganId"                => "string",
    "golonganLama"              => "string",
    "hukdisYangDiberhentikanId" => "string",
    "hukumanTanggal"            => "string",
    "id"                        => "string",
    "jenisHukumanId"            => "string",
    "jenisTingkatHukumanId"     => "string",
    "kedudukanHukumId"          => "string",
    "keterangan"                => "string",
    "masaBulan"                 => "string",
    "masaTahun"                 => "string",
    "nomorPp"                   => "string",
    "pnsOrangId"                => "string",
    "skNomor"                   => "string",
    "skPembatalanNomor"         => "string",
    "skPembatalanTanggal"       => "string",
    "skTanggal"                 => "string",
];
$response = $siasnClient->hukdis()
    ->create($data)
    ->includeDokumen("path/to/dokumen.pdf") //Hapus metod ini apabila tidak menggunakan dokumen
    ->save();

print_r($siasnClient->hukdis()->get($response['data']['id']));
```

## Kinerja Periodik

Berikut adalah daftar lengkap metode yang tersedia pada resource kinerja periodik:

| Metode                                                                          | Deskripsi                                             | Parameter                                                                              | Kembalian                     |
| ------------------------------------------------------------------------------- | ----------------------------------------------------- | -------------------------------------------------------------------------------------- | ----------------------------- |
| `$siasnClient->kinerjaPeriodik()->create($data)->save()`                        | Membuat data riwayat kinerja periodik tanpa dokumen.  | `$data` (array): data kinerja periodik                                                 | Array data kinerja periodik   |
| `$siasnClient->kinerjaPeriodik()->create($data)->includeDokumen($file)->save()` | Membuat data riwayat kinerja periodik dengan dokumen. | `$data` (array): data kinerja periodik<br>`$file` (string): path file atau URL dokumen | Array data kinerja periodik   |
| `$siasnClient->kinerjaPeriodik()->remove($idRiwayatKinerjaPeriodik)`            | Menghapus data riwayat kinerja periodik.              | `$idRiwayatKinerjaPeriodik` (string): ID riwayat kinerja periodik                      | Array atau `false` jika gagal |

#### Contoh Penggunaan Api Kinerja Periodik

```php
$data        = [
    "bulanMulaiPenilaian"   => "numeric",
    "bulanSelesaiPenilaian" => "numeric",
    "hasilKinerjaNilai"     => "referensi perilakuDanKinerja", //integer
    "koefisienId"           => "referensi koefisien",
    "kuadranKinerjaNilai"   => referensi kuadranNilai,
    "penilaiGolongan"       => "referensi golonganPns",
    "penilaiJabatanNama"    => "string",
    "penilaiNama"           => "string",
    "penilaiNipNrp"         => "string",
    "penilaiUnorNama"       => "string",
    "perilakuKerjaNilai"    => "referensi perilakuDanKinerja", //integer,
    "periodikId"            => "referensi periodik",
    "pnsDinilaiId"          => "string",
    "statusPenilai"         => "ASN/NON ASN",
    "tahun"                 => integer,
    "tahunMulaiPenilaian"   => integer,
    "tahunSelesaiPenilaian" => integer,
];

$response = $siasnClient->kinerjaPeriodik()
    ->create($data)
    ->includeDokumen("https://pdfobject.com/pdf/sample.pdf")
    ->save();

$deleted = $siasnClient->kinerjaPeriodik()->remove($response['data']['id']);
```

## Kursus

Berikut adalah daftar lengkap metode yang tersedia pada resource kursus:

| Metode                                                                 | Deskripsi                                     | Parameter                                                                    | Kembalian                     |
| ---------------------------------------------------------------------- | --------------------------------------------- | ---------------------------------------------------------------------------- | ----------------------------- |
| `$siasnClient->kursus()->get($idRiwayatKursus)`                        | Mengambil data riwayat kursus berdasarkan ID. | `$idRiwayatKursus` (string): ID riwayat kursus                               | Array data riwayat kursus     |
| `$siasnClient->kursus()->create($data)->save()`                        | Membuat data riwayat kursus tanpa dokumen.    | `$data` (array): data kursus                                                 | Array data kursus             |
| `$siasnClient->kursus()->create($data)->includeDokumen($file)->save()` | Membuat data riwayat kursus dengan dokumen.   | `$data` (array): data kursus<br>`$file` (string): path file atau URL dokumen | Array data kursus             |
| `$siasnClient->kursus()->remove($idRiwayatKursus)`                     | Menghapus data riwayat kursus.                | `$idRiwayatKursus` (string): ID riwayat kursus                               | Array atau `false` jika gagal |

#### Contoh Penggunaan Api Kursus

```php
$data = [
    "instansiId"             => "string",
    "institusiPenyelenggara" => "string",
    "jenisDiklatId"          => "referensi jenis diklat",
    "jenisKursus"            => "",
    "jenisKursusSertipikat"  => "string",
    "jumlahJam"              => integer,
    "lokasiId"               => "",
    "namaKursus"             => "string",
    "nomorSertipikat"        => "string",
    "pnsOrangId"             => "string",
    "tahunKursus"            => integer,
    "tanggalKursus"          => "string",
    "tanggalSelesaiKursus"   => "string",
];
$response = $siasnClient->kursus()->create($data)->save();
$kursus = $siasnClient->kursus()->get($response['data']['id']);

$siasnClient->kursus()->remove($response['data']['id']);
```

## SKP

Berikut adalah daftar lengkap metode yang tersedia pada resource skp:

## Daftar Metode SiASN Client untuk SKP

| Metode                                                                              | Deskripsi                                                     | Parameter                                                                                                                | Kembalian                                              |
| ----------------------------------------------------------------------------------- | ------------------------------------------------------------- | ------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------ |
| `$skp = $siasnClient->skp()->get($tahun, $idRiwayatSkp);`                           | Mengambil data SKP berdasarkan tahun dan ID riwayat SKP.      | `$tahun` (string): Tahun data SKP<br>`$idRiwayatSkp` (string): ID riwayat SKP                                            | Array data SKP sesuai dengan tahun dan ID riwayat SKP. |
| `$skp = $siasnClient->skp()->create($tahun, $data)->includeDokumen($file)->save();` | Membuat data SKP baru, menyertakan dokumen, dan menyimpannya. | `$tahun` (string): Tahun data SKP<br>`$data` (array): Data untuk dibuat<br>`$file` (mixed): Dokumen yang akan disertakan | Array data SKP yang disimpan setelah operasi berhasil. |

#### Contoh Penggunaan Api SKP

```php
#minyam data kursus
$response = $siasnClient->skp()
    ->create($tahun, $data)
    ->includeDokumen($file)
    ->save();

#mengambil data riwayat skp
$skp = $siasnClient->skp()->get($tahun, $idRiwayatSkp);

print_r($skp);
```

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
