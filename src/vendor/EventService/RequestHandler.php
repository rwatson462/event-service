<?php

namespace EventService;

use EventService\Http\Controller\PingController;
use EventService\Http\Controller\DebugController;
use EventService\Http\Controller\NotFoundController;
use EventService\Http\Controller\ReceiveEventController;
use EventService\Http\Controller\TokenController;
use SourcePot\Util\JSON;
use SourcePot\Bag\BagInterface;
use Swoole\Http\Request;
use Swoole\Http\Response;

class RequestHandler
{
    public function __construct(private BagInterface $config) {}

    public function handle(Request $request, Response $response): void
    {
        if($this->config->has('verbose')) {
            $start = microtime(true);
            echo "{$request->server['request_method']} request received!\n";
        }
    
        try {
            // Handle json bodies and attach to Requests's post property (it'll be empty by default)
            if ($request->header['contentType'] ?? '' === 'application/json') {
                $request->post = SwoolePostDataTransformer::toArray($request->getContent());
            }
    
            $url = rtrim($request->server['request_uri'], '/');
    
            $controller = match($url) {
                '/ping' => (new PingController)->__invoke($request, $response),
                '/token' => (new TokenController)->__invoke($request, $response),
                '/debug' => (new DebugController)->__invoke($request, $response),
                '/event' => (new ReceiveEventController)->__invoke($request, $response),
                default => (new NotFoundController)->__invoke($request,$response)
            };
        }
        catch (\Throwable $t) {
            // log the error
            echo $t->getMessage();
    
            // send the error back to the client
            $response->header('content-type', 'text/plain');
            $response->status(500);
            $response->write('error');
        }
        finally {
            if($this->config->has('verbose')) {
                $end = (microtime(true) - $start) * 1000;
                echo 'Memory used: '
                    . number_format(memory_get_usage() / 1024 / 1024, 2)
                    . 'MB, time taken: '
                    . number_format($end, 4, '.', '')
                    . "ms\n";
            }
        
            $response->end();
        }
    }
}
