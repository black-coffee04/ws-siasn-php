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

$siasnClient = new SiasnClient($config);


/**
 * Contoh Pengambilan Access Token WSO
 */
echo $siasnClient->wsoAccessToken() . PHP_EOL . PHP_EOL;

/**
 * Contoh Pengambilan Access Token SSO
 */
echo $siasnClient->ssoAccessToken() . PHP_EOL . PHP_EOL;