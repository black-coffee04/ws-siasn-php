<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
];

$siasn = new SiasnClient($config);


/**
 * Contoh Pengambilan Access Token WSO
 */
echo $siasn->authentication()->getWsoAccessToken() . PHP_EOL . PHP_EOL;
echo $siasn->authentication()->getSsoAccessToken() . PHP_EOL . PHP_EOL;
