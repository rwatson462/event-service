<?php

namespace SourcePot\Util;

class Bag
{
    public function __construct(
        private array $contents = []
    ) {
        // Filter out any non-string values then convert them to lower case,
        // Then convert keys to lower case
        $this->contents = array_change_key_case(
            array_map(
                static fn($value) => strtolower($value),
                array_filter(
                    $contents,
                    static fn($item) => is_string($item)
                )
            )
        );
    }

    public function has(string $key): bool
    {
        return array_key_exists(strtolower($key), $this->contents);
    }

    public function get(string $key, ?string $defaultValue = null): ?string
    {
        return $this->contents[strtolower($key)] ?? $defaultValue;
    }

    public function set(string $key, string $value): void
    {
        $this->contents[strtolower($key)] = $value;
    }

    public function all(): array
    {
        return [...$this->contents];
    }
}
