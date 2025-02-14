<?php

$envFile = '../.env';

if (file_exists($envFile)) {
    $envVariables = parse_ini_file($envFile);
} else {
    die('.env file not found');
}

foreach ($envVariables as $key => $value) {
    putenv("$key=$value");
}

require_once '../vendor/autoload.php';
