<?php

namespace SourcePot\Bag;

class Bag implements BagInterface
{
    public function __construct(
        protected array $contents = []
    ) {
        // Filter out any non-string values
        // Convert remaining values to lower case,
        // Convert keys to lower case
        $this->contents = array_change_key_case(
            array_map(
                static fn($value) => strtolower($value),
                array_filter(
                    $contents,
                    static fn($item) => is_string($item)
                )
            ),
            CASE_LOWER
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
        // copy the contents of the bag when returning so they cannot be mutable
        return [...$this->contents];
    }
}
