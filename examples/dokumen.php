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

$idRefDokumen = '872';
$dokumen  = 'https://pdfobject.com/pdf/sample.pdf';
$response = $siasn->dokumen()->upload($idRefDokumen, $dokumen);

//Save ke file/local server
$path = __DIR__ . DIRECTORY_SEPARATOR;
// echo $siasn->dokumen()->download($response)->setName('jabatan')->saveTo($path);

//Tampilkan dokumen tanpa menyimpan
$siasn->dokumen()->download($response)->setName('jabatan')->outputStream();