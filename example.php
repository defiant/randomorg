<?php

require_once __DIR__ . '/vendor/autoload.php';

$url = 'https://api.random.org/json-rpc/1/invoke';

$client = new \RandomOrg\Client($url);

$random = new \RandomOrg\Random($client);

var_dump($random->generateIntegers(100, 1, 200));
