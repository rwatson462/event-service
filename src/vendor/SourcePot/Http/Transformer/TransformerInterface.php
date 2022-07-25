<?php

namespace SourcePot\Http\Transformer;

interface TransformerInterface
{
    public function toArray(object $obj): array;
    public function fromArray(array $array): mixed;
}