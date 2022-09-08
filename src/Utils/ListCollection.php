<?php
declare(strict_types=1);

namespace Utils;

class ListCollection implements Collection
{
    private array $map;

    public function add(float|object|int|array|string $value): Collection
    {
        $this->map[] = $value;
        return $this;
    }

    public function getByIndex(int $key): string|float|int|array|object
    {
        if (!isset($this->map[$key])) {
            throw new \InvalidArgumentException(
                sprintf('Undefined map index "%s"', $key));
        }
    }

    public function getList(): array
    {
        return $this->map;
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
