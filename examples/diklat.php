<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
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
$response = $siasnClient->diklat()
    ->create($data)
    ->includeDokumen("file.pdf") //Hapus methods ini jika tidak menggunakan dokumen
    ->save();

$riwayatDiklat = $siasnClient->diklat()->get($response['data']['id']);
print_r($riwayatDiklat);

$riwayatDiklat = $siasnClient->diklat()->remove($response['data']['id']);
