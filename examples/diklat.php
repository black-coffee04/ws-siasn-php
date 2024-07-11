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
$idRiwayatDiklat = $siasnClient->diklat()
    ->create($data)
    ->includeDokumen("C:\Users\MLD - Hitam\Desktop\sample.pdf") //Hapus methods ini jika tidak menggunakan dokumen
    ->save();

$riwayatDiklat = $siasnClient->diklat()->get($idRiwayatDiklat);
print_r($riwayatDiklat);

if ($siasnClient->diklat()->remove($idRiwayatDiklat)) {
    echo "Riwayat diklat berhasil dihapus";
}