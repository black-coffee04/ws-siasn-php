<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
];

$siasnClient   = new SiasnClient($config);

$tahunAnggaran = '2023';

$daftarPengadaan  = $siasnClient->pengadaan()->get($tahunAnggaran);
$dokumenPengadaan = $siasnClient->pengadaan()->dokumen();

var_dump($daftarPengadaan);
