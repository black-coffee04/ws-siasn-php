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

$response = $siasn->dokumen()->upload('872', 'path\to\esample.pdf');

//Save ke file/local server
$path = __DIR__ . DIRECTORY_SEPARATOR;
echo $siasn->dokumen()->download($response)->setFileName('jabatan')->saveTo($path);

//Tampilkan dokumen tanpa menyimpan
$siasn->dokumen()->download($response)->setFileName('jabatan')->outputStream();