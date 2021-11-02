<?php
declare(strict_types=1);

namespace Database;

class Search
{
    public function __construct(private string $key, private string|array|int|float|null $value){}

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): float|int|array|string
    {
        return $this->value;
    }
}
