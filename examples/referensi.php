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
 * Contoh Referensi Unor
 * @param boolean $cache atur menjadi true apabila anda akan menyimpannya ke cache, cache akan expired dalam 1 jam
 */
$cache = true;
// echo json_encode($siasnClient->referensi()->unor($cache));
var_dump($siasnClient->referensi()->golonganPns());