<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
];

$nipAsn      = getenv('NIP');
$siasn       = new SiasnClient($config);

$jabatan = $siasn->jabatan()->pns($nipAsn);
$riwayatJabatan = $siasn->jabatan()->riwayat($jabatan['data'][0]['id']);
