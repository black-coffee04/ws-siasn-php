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
$periode       = '2023-04-01';

$daftarKenaikanPangkat = $siasnClient->kp()->get($periode);

var_dump($daftarKenaikanPangkat);