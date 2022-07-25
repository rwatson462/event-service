<?php

namespace SourcePot\Http\Transformer;

use InvalidArgumentException;
use JsonException;
use SourcePot\Util\JSON;

class SwoolePostDataTransformer implements TransformerInterface
{
    public function toArray(object $obj): array
    {
        try {
            return JSON::parse($request->getContent());
        }
        catch (JsonException $e) {         
            $response->status(400, 'Invalid JSON body');
            $response->write('Invalid JSON body');

            throw new InvalidArgumentException('Error decoding JSON body');
        }
    }

    public function fromArray(array $array): mixed
    {

    }
}