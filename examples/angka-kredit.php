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
    "bulanMulaiPenailan"   => "string",
    "bulanSelesaiPenailan" => "string",
    "isAngkaKreditPertama" => "string",
    "isIntegrasi"          => "",
    "isKonversi"           => "",
    "kreditBaruTotal"      => "string",
    "kreditPenunjangBaru"  => "string",
    "kreditUtamaBaru"      => "string",
    "nomorSk"              => "string",
    "pnsId"                => "string",
    "rwJabatanId"          => "string",
    "tahunMulaiPenailan"   => "string",
    "tahunSelesaiPenailan" => "string",
    "tanggalSk"            => "string",
];

$idRiwayatAngkaKredit = $siasnClient->angkaKredit()
    ->create($data)
    ->includeDokumen("http://url/to/dokumen.pdf")
    ->save();

$angkaKredit = $siasnClient->angkaKredit()->get($idRiwayatAngkaKredit);
$angkaKredit = $siasnClient->angkaKredit()->remove($idRiwayatAngkaKredit);
var_dump($angkaKredit);die();