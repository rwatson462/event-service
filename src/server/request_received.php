<?php

use SourcePot\Util\Bag;
use SourcePot\Util\JSON;
use Swoole\Http\Request;
use Swoole\Http\Response;

function requestReceived(Request $request, Response $response): void
{
    echo "{$request->server['request_method']} request received!\n";

    $headerBag = new Bag($request->header);

    // Handle json bodies from anything but GET requests
    if ($request->server['request_method'] !== 'GET'
        && $headerBag->has('content-type')
        && $headerBag->get('content-type') === 'application/json'
    ) {
        try {
            $request->post = JSON::parse($request->getContent());
        }
        catch (JsonException $e) {
            // Debug to terminal output
            echo "Error decoding JSON body\n";
            
            // Send response to client
            $response->status(400, 'Invalid JSON body');
            $response->end('Invalid JSON body');

            // Process no more of this request
            return;
        }
    }

    $postData = new Bag($request->post ?? []);

    $url = rtrim($request->server['request_uri'], '/');

    // Handle basic routing
    if ($url === '/ping') {
        $response->header('content-type', 'text/plain');
        $response->end('pong');
        return;
    }

    if ($url === '/debug') {
        debugOutput($request, $response, $headerBag, $postData);
        return;
    }

    // check for api key and validate
    $user_key = $headerBag->get('api-key');
    if (!$user_key) {
        $response->status(400);
        $response->end('Missing API key');
        return;
    }

    $redis = new Redis;
    $redis->connect('redis');
    $key = $redis->get('api-key') ?: '';
    if ($user_key !== $key) {
        $response->status(400);
        $response->end('Invalid API key');
        return;
    }
    
    echo "Post data received:\n";
    print_r($postData);

    echo match($request->server['request_method']) {
        'POST' => "POST request\n",
        default => "Unsupported request method\n"
    };

    $response->header("Content-Type", "text/plain");
    $response->end("Hello World");
}


// todo remove this debugging output one day
function debugOutput(Request $request, Response $response, Bag $headerBag, Bag $postData): void {
    $redis = new Redis;
    $redis->connect('redis');
    $response->header('Content-type', 'text/plain');
    $response->end( JSON::prettify([
        'header' => $headerBag->all(),
        'server' => $request->server,
        'post' => $postData->all(),
        'api-key' => $redis->get('api-key'),
        'redis-keys' => $redis->keys('*'),
    ]));
}
