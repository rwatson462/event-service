<?php

namespace SourcePot\Bag;

class ReadOnlyBag extends Bag
{
    public function set(string $key, string $value): void
    {
        $lowerKey = strtolower($key);

        if(array_key_exists($lowerKey, $this->contents)) {
            throw new \InvalidArgumentException(
                'Cannot change the value of a readonly bag item'
            );
        }

        $this->contents[$lowerKey] = $value;
    }
}
