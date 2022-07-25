<?php

use EventService\Http\Controller\PingController;
use EventService\Http\Controller\DebugController;
use EventService\Http\Controller\NotFoundController;
use EventService\Http\Controller\ReceiveEventController;
use SourcePot\Bag\ReadOnlyBag;
use SourcePot\Util\JSON;
use Swoole\Http\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

// This is my own super-special autoloader function that registers itself
require dirname(__DIR__).'/vendor/autoloader.php';

$config = new ReadOnlyBag(parse_ini_file(__DIR__.'/conf.ini'));

$server = new Server($config->get('host'), $config->get('port'));

$server->on("Start", static function(Server $server) use($config) {
    echo 'Swoole http server is started at http://'
        . $config->get('host')
        . ':'
        . $config->get('port')
        . "\n";
});

$server->on("Request", static function(Request $request, Response $response) use ($config) {
    $start = microtime(true);
    echo "{$request->server['request_method']} request received!\n";

    try {
        // Handle json bodies and attach to Requests's post property (it'll be empty by default)
        if ($request->header['contentType'] ?? '' === 'application/json') {
            $request->post = SwoolePostDataTransformer::toArray($request->getContent());
        }

        $url = rtrim($request->server['request_uri'], '/');

        $controller = match($url) {
            '/ping' => (new PingController)->__invoke($request, $response),
            '/debug' => (new DebugController)->__invoke($request, $response),
            '/event' => (new ReceiveEventController)->__invoke($request, $response),
            default => (new NotFoundController)->__invoke($request,$response)
        };
    }
    catch (\Throwable $t) {
        // log the error
        echo $t->getMessage();

        // send the error back to the client
        // @todo remove this eventually
        $response->write($t->getMessage());
    }
    finally {
        if($config->has('verbose')) {
            $end = (microtime(true) - $start) * 1000;
            echo 'Memory used: '
                . number_format(memory_get_usage() / 1024 / 1024, 2)
                . 'MB, time taken: '
                . number_format($end, 4, '.', '')
                . "ms\n";
        }
    
        $response->end();
    }
});

$server->start();
