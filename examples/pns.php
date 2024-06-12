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
$nipAsn      = getenv('NIP');
$pnsOrangId  = getenv('PNS_ORANG_ID');

/**
 * Mengambil data utama ASN
 * @return array data asn
 */
echo "============= DATA UTAMA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->getDataUtama($nipAsn));

/**
 * Mengambil data pasangan ASN
 * @return array data pasangan asn
 */
echo "============= DATA PASANGAN ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->getDataPasangan($nipAsn));

/**
 * Mengambil data anak ASN
 * @return array data
 */
echo "============= DATA ANAK ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->getDataAnak($nipAsn));

/**
 * Mengambil data Orang tua
 * @return array data
 */
echo "============= DATA ORANG TUA ASN ============" . PHP_EOL . PHP_EOL;
echo json_encode($siasnClient->pns()->getDataOrangTua($nipAsn));

/**
 * Refresh Data Jabatan & Golongan
 * @return boolean
 */

echo $siasnClient->pns()->refreshJabatan($pnsOrangId);
echo $siasnClient->pns()->refreshGolongan($pnsOrangId);

echo json_encode($siasnClient->pns()->getNilaiIpAsn($nipAsn));

var_dump($siasnClient->pns()->getFoto($pnsOrangId));