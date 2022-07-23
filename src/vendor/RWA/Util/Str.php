<?php

namespace RWA\Util;

class Str
{

    // Instantation methods

    public function __construct(
        private string $value
    ) {
    }

    public static function from(string $s): self
    {
        return new self($s);
    }

    public function copy(): self
    {
        return new self($this->value);
    }

    // To access the primitive string this object contains
    public function unwrap(): string
    {
        return $this->value;
    }

    // These functions describe the stored string

    public function length(): int
    {
        return mb_strlen($this->value);
    }

    public function startsWith(Str|string $other): bool
    {
        if($other instanceof Str) {
            $other = $other->unwrap();
        }

        return str_starts_with($this->value, $other);
    }

    public function endsWith(Str|string $other): bool
    {
        if($other instanceof Str) {
            $other = $other->unwrap();
        }

        return str_ends_with($this->value, $other);
    }

    // These functions compare to other strings

    public function equals(Str|string $other): bool
    {
        if($other instanceof Str) {
            return $this->value === $other->unwrap();
        }

        return $this->value === $other;
    }

    public function compareTo(Str|string $other): int
    {
        if($other instanceof Str) {
            $other = $other->unwrap();
        }

        return strcmp($this->value, $other);
    }

    // These function return new Str objects with changes made to the original
    
    public function toLower(): self
    {
        return new self(mb_strtolower($this->value));
    }

    public function toUpper(): self
    {
        return new self(mb_strtoupper($this->value));
    }

    public function slice(int $start, ?int $length = null): self
    {
        if ($length === null) {
            return new self(mb_substr($this->value, $start));
        }

        return new self(mb_substr($this->value, $start, $length));
    }
}