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

$siasn = new SiasnClient($config);


/**
 * Contoh Pengambilan Access Token WSO
 */

$referensi        = $siasn->referensi();
$searchTerm       = 'layanan operasional';
$limit            = 10;

$agama = $siasn
    ->referensi()
    ->agama(true)
    ->like('is')
    ->search('nama')
    ->get();

var_dump($agama);