<?php

namespace SourcePot\Bag;

interface BagInterface
{
    public function has(string $key): bool;
    public function get(string $key, ?string $defaultValue = null): ?string;
    public function set(string $key, string $value): void;

    // May remove this in the future as it's more for debug
    public function all(): array;
}