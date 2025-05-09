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
