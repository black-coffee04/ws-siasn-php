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
$periode       = '2023-04-01';

$daftarKenaikanPangkat = $siasnClient->kp()->get($periode);

var_dump($daftarKenaikanPangkat);
