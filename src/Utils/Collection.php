<?php
declare(strict_types=1);

namespace Utils;

use InvalidArgumentException;

interface Collection
{
    /**
     * Add a new element to the with a key to the map
     */
    public function add(string|float|int|array|object $value): Collection;

    /**
     * Returns an element which maps to the provided key
     *
     * @throws InvalidArgumentException
     */
    public function getByIndex(int $key): string|float|int|array|object;

    /**
     * Returns an array of all added elements
     */
    public function getList(): array;

    /**
     * Returns the count of all added elements
     */
    public function count(): int;

    /**
     * Moves the pointer of the map to the next element
     */
    public function next(): void;

    /**
     * Move the pointer back to the first element
     */
    public function rewind(): void;

    /**
     * Erase the map elements
     */
    public function flush(): void;

}
