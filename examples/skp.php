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

$siasnClient  = new SiasnClient($config);
$tahun        = "2023";
$idRiwayatSkp = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
$data         = [];
$file         = "path/to/skp.pdf";

$skp   = $siasnClient->skp()->get($tahun, $idRiwayatSkp);
$skp   = $siasnClient->skp()->create($tahun, $data)->includeDokumen($file)->save();
var_dump($skp);