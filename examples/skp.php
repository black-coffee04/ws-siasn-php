<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
];

$siasnClient  = new SiasnClient($config);
$tahun        = "2023";
$idRiwayatSkp = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$data         = [];
$file         = "path/to/skp.pdf";

$skp   = $siasnClient->skp()->get($tahun, $idRiwayatSkp);
$skp   = $siasnClient->skp()->create($tahun, $data)->includeDokumen($file)->save();
var_dump($skp);
