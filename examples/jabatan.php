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

$nipAsn      = getenv('NIP');
$siasn       = new SiasnClient($config);

$jabatan = $siasn->jabatan()->pns($nipAsn);
echo json_encode($jabatan) . PHP_EOL . PHP_EOL;
echo json_encode($siasn->jabatan()->riwayat($jabatan['0']['id']));

$dokumenUrl = 'https://pdfobject.com/pdf/sample.pdf';
$dokumen    = 'G:\invoicesample.pdf';
$unorData   = [];

$riwayatUnorJabatanId = $siasn->jabatan()
    ->createUnorJabatan($unorData)
    ->includeDokumen($dokumenUrl)
    ->save();

if ($siasn->jabatan()->remove($riwayatUnorJabatanId)) {
    echo "Unor Jabatan Berhasil Dihapus.";
}

$data = [];

$riwayatJabatanId = $siasn->jabatan()
    ->create($data)
    ->includeDokumen($dokumen)
    ->save();

if ($siasn->jabatan()->remove($riwayatJabatanId)) {
    echo "Jabatan Berhasil Dihapus.";
}