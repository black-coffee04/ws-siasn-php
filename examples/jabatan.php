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

$nipAsn      = getenv('NIP');
$siasn       = new SiasnClient($config);

$jabatan = $siasn->jabatan()->pns($nipAsn);
echo json_encode($jabatan) . PHP_EOL . PHP_EOL;
echo json_encode($siasn->jabatan()->riwayat($jabatan['0']['id']));