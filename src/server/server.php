<?php

use Swoole\Http\Server;

// This is my own super-special autoloader function that registers itself
require dirname(__DIR__).'/vendor/autoloader.php';

require __DIR__.'/request_received.php';

const HOST = '0.0.0.0';
const PORT = 9501;

$server = new Server(HOST, PORT);

$server->on("Start", function(Server $server) {
    echo 'Swoole http server is started at http://' . HOST . ':' . PORT . "\n";
});

$server->on("Request", 'requestReceived');
$server->start();
