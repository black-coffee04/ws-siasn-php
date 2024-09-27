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
$data        = [
    "bulanMulaiPenilaian"   => "numeric",
    "bulanSelesaiPenilaian" => "numeric",
    "hasilKinerjaNilai"     => "referensi perilakuDanKinerja", //integer
    "koefisienId"           => "referensi koefisien",
    "kuadranKinerjaNilai"   => referensi kuadranNilai,
    "penilaiGolongan"       => "referensi golonganPns",
    "penilaiJabatanNama"    => "string",
    "penilaiNama"           => "string",
    "penilaiNipNrp"         => "string",
    "penilaiUnorNama"       => "string",
    "perilakuKerjaNilai"    => "referensi perilakuDanKinerja", //integer,
    "periodikId"            => "referensi periodik",
    "pnsDinilaiId"          => "string",
    "statusPenilai"         => "ASN/NON ASN",
    "tahun"                 => integer,
    "tahunMulaiPenilaian"   => integer,
    "tahunSelesaiPenilaian" => integer,
];

$response = $siasnClient->kinerjaPeriodik()
    ->create($data)
    ->includeDokumen("https://pdfobject.com/pdf/sample.pdf")
    ->save();

$deleted = $siasnClient->kinerjaPeriodik()->remove($response['data']['id']);