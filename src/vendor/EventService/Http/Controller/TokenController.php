<?php

namespace EventService\Http\Controller;

use Redis;
use SourcePot\Http\Controller\ControllerInterface;
use SourcePot\JWT\Token;
use SourcePot\Util\JSON;
use Swoole\Http\Request;
use Swoole\Http\Response;

class TokenController implements ControllerInterface
{
    public function __invoke(Request $request, Response $response): void
    {
        $apikey = $request->header['api-key'] ?? null;

        if($apikey === null) {
            $response->status(400);
            $response->write('No apikey given');
            return;
        }

        $clientName = $request->header['client'] ?? null;

        if($clientName === null) {
            $response->status(400);
            $response->write('No client name given');
            return;
        }

        $redis = new Redis;
        $redis->connect('redis');
        $events = $redis->get($clientName . '.' . $apikey);

        if($events === false) {
            $response->status(401);
            $response->write('Client not registered');
            return;
        }

        $events = JSON::parse($events);

        $token = Token::create(payload: [
            'client' => $clientName,
            'events' => $events
        ]);

        $token->setExpiry(strtotime('now +24 hours'));

        $secret = $redis->get('secret');
        $token->sign($secret);

        $response->write((string) $token);
    }
}
