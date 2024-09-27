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
#Menyimpan data penghargaan
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
$siasnClient->penghargaan()->remove($idRiwayatPenghargaan);
