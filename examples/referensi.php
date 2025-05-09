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

$referensi  = $siasn->referensi();
$attributes = 'NamaUnor';
$keyword    = 'puskesmas';
$limit      = 10;

$unor = $siasn
    ->referensi()
    ->penghargaan()->get();

var_dump($unor);
