<?php

use SourcePot\Bag\ReadOnlyBag;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

// This is my own super-special autoloader function that registers itself
require dirname(__DIR__).'/vendor/autoloader.php';

require __DIR__.'/request_received.php';

$config = new ReadOnlyBag(parse_ini_file(__DIR__.'/conf.ini'));

$server = new Server($config->get('host'), $config->get('port'));

$server->on("Start", function(Server $server) use($config) {
    echo 'Swoole http server is started at http://'
        . $config->get('host')
        . ':'
        . $config->get('port')
        . "\n";
});

$server->on("Request", function(Request $request, Response $response) use ($config) {
    try {
        requestReceived($request, $response);
    } catch (Throwable $t) {
        echo $t->getMessage();
    }

    if($config->has('verbose')) {
        echo "Memory used: " . number_format(memory_get_usage()/1024/1024, 2) . "M\n";
    }
    $response->end();
});

$server->start();
