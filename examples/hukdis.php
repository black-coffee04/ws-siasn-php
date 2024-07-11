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

$siasnClient     = new SiasnClient($config);
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
$idRiwayatHukdis = $siasnClient->hukdis()
    ->create($data)
    ->includeDokumen("path/to/dokumen.pdf") //Hapus metod ini apabila tidak menggunakan dokumen
    ->save();

print_r($siasnClient->hukdis()->get($idRiwayatHukdis));