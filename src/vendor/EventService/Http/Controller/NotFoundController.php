<?php

namespace EventService\Http\Controller;

use SourcePot\Http\Controller\ControllerInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

class NotFoundController implements ControllerInterface
{
    public function __invoke(Request $request, Response $response): void
    {
        // "404" route
        $response->header("Content-Type", "text/plain");
        $response->write("Hello World");
    }
}