<?php

/**
 * This file just displays all registered clients with their event permissions
 */

header('content-type: text/plain');

$redis = new Redis;
$redis->connect('redis');

var_dump($redis->keys('*'));

$clientKeys = $redis->keys('c_*');

$clients = [];

foreach($clientKeys as $clientKey) {
    $clientName = substr($clientKey, 2);
    $clients[$clientName] = [
        'apikey' => $redis->get("k_$clientName"),
        'events' => $redis->get($clientKey),
    ];
}

print_r($clients);