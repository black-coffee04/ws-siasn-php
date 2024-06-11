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
$nipAsn      = '199612052020121003';

/**
 * Mengambil data utama ASN
 * @return array data asn
 */
echo "============= DATA UTAMA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->dataUtama($nipAsn));

/**
 * Mengambil data pasangan ASN
 * @return array data pasangan asn
 */
echo "============= DATA UTAMA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->dataPasangan($nipAsn));

/**
 * Mengambil data anak ASN
 * @return array data anak asn
 */
echo "============= DATA UTAMA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->dataAnak($nipAsn));