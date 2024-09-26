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
$data = [
    "hargaId"    => "400",
    "pnsOrangId" => "8ae4838e6e847664016e847b87d40201",
    "skDate"     => "26-09-2024",
    "skNomor"    => "810/TEST/22/OK",
    "tahun"      => 2024,
];
$urlfile = "https://sipgan.magelangkab.go.id/sipgan/document/efile/2024/199002232018011002_teknis_04042024_131654.pdf";
$response = $siasnClient->penghargaan()->create($data)->includeDokumen($urlfile)->save();
if (isset($response['success']) && $response['success']) {
    $penghargaan = $siasnClient->penghargaan()->get($response['data']['id']);
    var_dump($response);
}
echo "\n\n";
$penghargaan = $siasnClient->penghargaan()->remove('d1a761a6-7bce-11ef-949f-0a580a820a3b');
var_dump($penghargaan);
die();
