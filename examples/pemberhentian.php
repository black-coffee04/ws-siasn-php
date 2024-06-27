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

$siasnClient   = new SiasnClient($config);
$tanggalAwal   = '2022-01-01';
$tanggalAkhir  = '2022-12-01';

$daftarPemberhentian   = $siasnClient->pemberhentian()->get($tanggalAwal, $tanggalAkhir);

var_dump($daftarPemberhentian);