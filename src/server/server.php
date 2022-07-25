<?php

use EventService\RequestHandler;
use SourcePot\Bag\ReadOnlyBag;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

// This is my own super-special autoloader function that registers itself
require dirname(__DIR__).'/vendor/autoloader.php';

$config = new ReadOnlyBag(parse_ini_file(__DIR__.'/conf.ini'));
$server = new Server($config->get('host'), $config->get('port'));
$handler = new RequestHandler($config);

$server->on("Start", static function(Server $server) use($config) {
    echo 'Swoole http server is started at '
        . 'http://'. $config->get('host') . ':' . $config->get('port') . "\n";
});

$server->on("Request", [$handler, 'handle']);

$server->start();
