<?php

use EventService\Http\Controller\PingController;
use EventService\Http\Controller\DebugController;
use EventService\Http\Controller\NotFoundController;
use EventService\Http\Controller\ReceiveEventController;
use SourcePot\Bag\ReadOnlyBag;
use SourcePot\Util\JSON;
use Swoole\Http\Request;
use Swoole\Http\Response;

function requestReceived(Request $request, Response $response): void
{
    echo "{$request->server['request_method']} request received!\n";

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
