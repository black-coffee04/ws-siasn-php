<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
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
$response = $siasnClient->hukdis()
    ->create($data)
    ->includeDokumen("path/to/dokumen.pdf") //Hapus metod ini apabila tidak menggunakan dokumen
    ->save();

print_r($siasnClient->hukdis()->get($response['data']['id']));
