<?php

namespace EventService\Http\Controller;

use Redis;
use SourcePot\Bag\ReadOnlyBag;
use SourcePot\Http\Controller\ControllerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

class ReceiveEventController implements ControllerInterface
{
    public function __invoke(Request $request, Response $response): void
    {
        $headerBag = new ReadOnlyBag($request->header);
        $postData = new ReadOnlyBag($request->post ?? []);

        // check for api key and validate
        $user_key = $headerBag->get('api-key');
        if (!$user_key) {
            $response->status(400);
            $response->write('Missing API key');
            return;
        }

        $redis = new Redis;
        $redis->connect('redis');
        $key = $redis->get('api-key') ?: '';
        if ($user_key !== $key) {
            $response->status(400);
            $response->write('Invalid API key');
            return;
        }

        echo "Post data received:\n";
        print_r($postData->all());
    }
}
