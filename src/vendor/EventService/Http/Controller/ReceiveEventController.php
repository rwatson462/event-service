<?php

namespace EventService\Http\Controller;

use Redis;
use SourcePot\Http\Controller\ControllerInterface;
use SourcePot\JWT\Token;
use SourcePot\Util\JSON;
use Swoole\Http\Request;
use Swoole\Http\Response;

class ReceiveEventController implements ControllerInterface
{
    public function __invoke(Request $request, Response $response): void
    {
        $token = $request->header['token'] ?? null;
        if ($token === null) {
            $response->status(400);
            $response->write('Missing login token');
            return;
        }

        $token = Token::from($token);

        $redis = new Redis;
        $redis->connect('redis');

        $secret = $redis->get('secret');

        /**
         * This is the secret to confirming the validity of a token.  The server previously signed
         * this token using a secret, the same secret we use here to re-sign the contents and check
         * that the signature we generate matches the signature the client sent us.  If they match,
         * the token is the same token we previously generated and has not been changed.  We can
         * trust the contents of it without needing to make further database queries to generate
         * the contents of the token.
         */
        $token->validate($secret);

        echo "Post data received:\n";
        echo JSON::stringify(['header' => $token->getHeader(), 'payload' => $token->getPayload()])."\n";

        // todo We expect an event to be passed in via json post data so we should capture it
        // todo create an Event object containing the properties from post
        // todo dispatch the Event using some clever system
        // todo if successful, tell the client
        $eventName = $request->post['event'];

        $response->write("event $eventName accepted");
    }
}
