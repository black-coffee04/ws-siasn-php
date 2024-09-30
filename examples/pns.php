<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => getenv('CONSUMER_KEY'),
    "consumerSecret" => getenv('CONSUMER_SECRET'),
    "clientId"       => getenv('CLIENT_ID'),
    "username"       => getenv('USERNAME_SSO'),
    "password"       => getenv('PASSWORD')
];

$nipAsn = getenv('NIP');
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
$update = $siasn->pns()->update($nipAsn);
var_dump($update);
