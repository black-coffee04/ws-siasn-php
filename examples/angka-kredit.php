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