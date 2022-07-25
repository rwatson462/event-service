<?php

namespace EventService\Http\Controller;

use SourcePot\Http\Controller\ControllerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

class PingController implements ControllerInterface
{
    public function __invoke(Request $request, Response $response): void
    {
        $response->header('content-type', 'text/plain');
        $response->write('pong');
    }
}