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

$siasnClient = new SiasnClient($config);
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
$idRiwayatKursus = $siasnClient->kursus()->create($data)->save();
$kursus = $siasnClient->kursus()->get($idRiwayatKursus);
var_dump($kursus);
if ($siasnClient->kursus()->remove($idRiwayatKursus)) {
    echo "Sukses Hapus kursus";
}
