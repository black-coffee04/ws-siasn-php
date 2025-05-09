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
