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

$nipAsn = getenv('NIP');
$siasn  = new SiasnClient($config);

#Mengambil Data Utama
$dataUtama = $siasn->pns()->dataUtama($nipAsn);
var_dump($dataUtama);
