<?php

$envFile = __DIR__ . '../../.env';

if (file_exists($envFile)) {
    $envVariables = parse_ini_file($envFile);
} else {
    die('.env file not found');
}

foreach ($envVariables as $key => $value) {
    putenv("$key=$value");
}

require_once __DIR__ . './../vendor/autoload.php';

$config = [
    "consumerKey"    => getenv('CONSUMER_KEY'),
    "consumerSecret" => getenv('CONSUMER_SECRET'),
    "clientId"       => getenv('CLIENT_ID'),
    "username"       => getenv('USERNAME_SSO'),
    "password"       => getenv('PASSWORD')
];