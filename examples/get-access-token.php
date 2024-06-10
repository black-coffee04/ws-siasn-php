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

$wsSiASN = new SiasnClient($config);


/**
 * Contoh Pengambilan Access Token WSO
 */
echo $wsSiASN->wsoAccessToken() . PHP_EOL . PHP_EOL;

/**
 * Contoh Pengambilan Access Token SSO
 */
echo $wsSiASN->ssoAccessToken() . PHP_EOL . PHP_EOL;