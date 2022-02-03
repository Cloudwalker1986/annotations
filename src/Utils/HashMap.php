<?php
declare(strict_types=1);

namespace Utils;

class HashMap implements Map
{
    private array $map = [];

    public function add(string $key, float|object|int|array|string $value): Map
    {
        $this->map[$key] = $value;
        return $this;
    }

    public function get(string $key): string|float|int|array|object
    {
        if (!isset($this->map[$key])) {
            throw new \InvalidArgumentException(
                sprintf('Undefined map index "%s"', $key));
        }

        return $this->map[$key];
    }

    public function count(): int
    {
        return count($this->map);
    }

    public function next(): void
    {
        next($this->map);
    }

    public function rewind(): void
    {
        reset($this->map);
    }

    public function flush(): void
    {
        $this->map = [];
    }
}
