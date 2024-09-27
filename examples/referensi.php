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

$referensi  = $siasn->referensi();
$attributes = 'NamaUnor';
$keyword    = 'puskesmas';
$limit      = 10;

$unor = $siasn
    ->referensi()
    ->penghargaan()->get();

var_dump($unor);