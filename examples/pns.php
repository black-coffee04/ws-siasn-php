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

$nipAsn = getenv('NIP');
$siasn  = new SiasnClient($config);
$pns    = $siasn->pns();

/**
 * Mengambil data utama ASN
 * @return array data asn
 */
echo "============= DATA UTAMA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($pns->dataUtama($nipAsn)) . PHP_EOL . PHP_EOL;

/**
 * Mengambil data pasangan ASN
 * @return array data pasangan asn
 */
echo "============= DATA PASANGAN ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($pns->dataPasangan($nipAsn)) . PHP_EOL . PHP_EOL;

/**
 * Mengambil data anak ASN
 * @return array data
 */
echo "============= DATA ANAK ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($pns->dataAnak($nipAsn)) . PHP_EOL . PHP_EOL;

/**
 * Mengambil data Orang tua
 * @return array data
 */
echo "============= DATA ORANG TUA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($pns->dataOrangTua($nipAsn)) . PHP_EOL . PHP_EOL;

echo json_encode($pns->nilaiIpAsn($nipAsn)) . PHP_EOL . PHP_EOL;

// echo $pns->refreshJabatan($nipAsn);
// echo $pns->refreshGolongan($nipAsn);
// $path = __DIR__ . DIRECTORY_SEPARATOR;
// $pns->foto($nipAsn)->setFileName('profile')->outputStream();