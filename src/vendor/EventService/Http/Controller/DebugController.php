<?php

namespace EventService\Http\Controller;

use Redis;
use SourcePot\Bag\ReadOnlyBag;
use SourcePot\Http\Controller\ControllerInterface;
use SourcePot\Util\JSON;
use Swoole\Http\Request;
use Swoole\Http\Response;

class DebugController implements ControllerInterface
{
    public function __invoke(Request $request, Response $response): void
    {
        $headerBag = new ReadOnlyBag($request->header);
        $postData = new ReadOnlyBag($request->post ?? []);

        $redis = new Redis;
        $redis->connect('redis');
        $response->header('Content-type', 'text/plain');
        $response->write( JSON::prettify([
            'header' => $headerBag->all(),
            'server' => $request->server,
            'post' => $postData->all(),
            'api-key' => $redis->get('api-key'),
            'redis-keys' => $redis->keys('*'),
        ]));
    }
}
