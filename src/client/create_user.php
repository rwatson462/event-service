<?php

$redis = new Redis;
$redis->connect('redis');

$clientName = 'todo';
$apikey = '3dc5f12d6f3462bb960a152bf73f2e81';

$redis->set($clientName . '.' . $apikey, '["todo.created","todo.description.changed","todo.markedComplete","todo.deleted"]');