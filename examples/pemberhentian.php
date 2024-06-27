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
$tanggalAwal   = '2022-01-01';
$tanggalAkhir  = '2022-12-01';
// $periode       = '2023-04-01';
// $tahunAnggaran = '2023';

$daftarPemberhentian   = $siasnClient->pemberhentian()->get($tanggalAwal, $tanggalAkhir);
// $daftarKenaikanPangkat = $siasnClient->instansi()->kenaikanPangkat($periode);
// $daftarPengadaan = $siasnClient->instansi()->pengadaan($tahunAnggaran);
// $dokumenPengadaan = $siasnClient->instansi()->dokumen($tahunAnggaran);

var_dump($daftarPemberhentian);