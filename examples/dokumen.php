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

$idRefDokumen = '872';
$dokumen  = 'https://pdfobject.com/pdf/sample.pdf';
$response = $siasn->dokumen()->upload($idRefDokumen, $dokumen);

//Save ke file/local server
$path = __DIR__ . DIRECTORY_SEPARATOR;
// echo $siasn->dokumen()->download($response)->setName('jabatan')->saveTo($path);

//Tampilkan dokumen tanpa menyimpan
$siasn->dokumen()->download($response)->setName('jabatan')->outputStream();
