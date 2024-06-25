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
$args  = '{"dok_id":"872","dok_nama":"Dok SK Jabatan","dok_uri":"peremajaan\/usulan\/8ae4839a6e924c71016e958fdeee7c48_20240624_033111_sk-jabatan.pdf","slug":"872","object":"peremajaan\/usulan\/8ae4839a6e924c71016e958fdeee7c48_20240624_033111_sk-jabatan.pdf"}';
$args  = json_decode($args);
// $args = "peremajaan\/usulan\/8ae4839a6e924c71016e958fdeee7c48_20240624_033111_sk-jabatan.pdf";
$path = __DIR__ . DIRECTORY_SEPARATOR;
// echo $siasn->dokumen()->download($args)->setFileName('jabatan')->saveTo($path);

var_dump($siasn->dokumen()->upload('872', 'C:\Users\MLD - Hitam\Desktop\invoicesample.pdf'));