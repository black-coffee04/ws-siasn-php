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
$tanggalAwal   = '2022-01-01';
$tanggalAkhir  = '2022-12-01';

$daftarPemberhentian   = $siasnClient->pemberhentian()->get($tanggalAwal, $tanggalAkhir);

var_dump($daftarPemberhentian);
