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

$tahunAnggaran = '2023';

$daftarPengadaan  = $siasnClient->pengadaan()->get($tahunAnggaran);
$dokumenPengadaan = $siasnClient->pengadaan()->dokumen();

var_dump($daftarPengadaan);