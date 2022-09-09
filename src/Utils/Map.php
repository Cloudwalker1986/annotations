<?php
declare(strict_types=1);

namespace Utils;

interface Map
{
    /**
     * Add a new element to the with a key to the map
     */
    public function add(string $key, string|float|int|array|object $value): Map;

    /**
     * Returns an element which maps to the provided key
     *
     * @throws \InvalidArgumentException
     */
    public function get(string $key): string|float|int|array|object;

    /**
     * Returns a true when the key is already part of the hashmap and false if the key does not exists
     */
    public function has(string $key): bool;

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
