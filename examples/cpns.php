<?php

use SiASN\Sdk\SiasnClient;

require_once __DIR__ . './init.php';

$config = [
    "consumerKey"    => 'consumerKeyAnda',
    "consumerSecret" => 'consumerSecretAnda',
    "clientId"       => 'clientIdAnda',
    "ssoAccessToken" => 'ssoAccessTokenAnda'
];

$siasnClient = new SiasnClient($config);
$data = [
    "id"                           => "string",
    "kartu_pegawai"                => "string",
    "nama_jabatan_angkat_cpns"     => "string",
    "nomor_dokter_pns"             => "string",
    "nomor_sk_cpns"                => "string",
    "nomor_sk_pns"                 => "string",
    "nomor_spmt"                   => "string",
    "nomor_sttpl"                  => "string",
    "pertek_cpns_pns_l2th_nomor"   => "string",
    "pertek_cpns_pns_l2th_tanggal" => "string",
    "pns_orang_id"                 => "string",
    "status_cpns_pns"              => "string",
    "tanggal_dokter_pns"           => "string",
    "tgl_sk_cpns"                  => "string",
    "tgl_sk_pns"                   => "string",
    "tgl_sttpl"                    => "string",
    "tmt_pns"                      => "string",
];

$response = $siasnClient->cpns()
    ->create($data)
    ->includeDokumen("path/to/dokumen.pdf")
    ->save();
