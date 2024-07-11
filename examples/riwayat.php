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
$siasnClient = new SiasnClient($config);
$response    = [];

$response['angka_kredit']     = $siasnClient->riwayat()->angkaKredit($nipAsn);
$response['cltn']             = $siasnClient->riwayat()->cltn($nipAsn);
$response['diklat']           = $siasnClient->riwayat()->diklat($nipAsn);
$response['dp3']              = $siasnClient->riwayat()->dp3($nipAsn);
$response['golongan']         = $siasnClient->riwayat()->golongan($nipAsn);
$response['hukdis']           = $siasnClient->riwayat()->hukdis($nipAsn);
$response['jabatan']          = $siasnClient->riwayat()->jabatan($nipAsn);
$response['jabatan']          = $siasnClient->riwayat()->jabatan($nipAsn);
$response['kinerja_periodik'] = $siasnClient->riwayat()->kinerjaPeriodik($nipAsn);
$response['kursus']           = $siasnClient->riwayat()->kursus($nipAsn);
$response['masa_kerja']       = $siasnClient->riwayat()->masaKerja($nipAsn);
$response['pemberhentian']    = $siasnClient->riwayat()->pemberhentian($nipAsn);
$response['pendidikan']       = $siasnClient->riwayat()->pendidikan($nipAsn);
$response['penghargaan']      = $siasnClient->riwayat()->penghargaan($nipAsn);
$response['pindah_instansi']  = $siasnClient->riwayat()->pindahInstansi($nipAsn);
$response['unor']             = $siasnClient->riwayat()->unor($nipAsn);
$response['pwk']              = $siasnClient->riwayat()->pwk($nipAsn);
$response['skp']              = $siasnClient->riwayat()->skp($nipAsn);
$response['skp22']            = $siasnClient->riwayat()->skp22($nipAsn);

var_dump($response);die();