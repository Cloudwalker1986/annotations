<?php
declare(strict_types=1);

namespace Utils;

use InvalidArgumentException;

interface Collection extends \Iterator
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
     * Please do not use this function anymore since Collection interface is already extending the Iterator interface
     * @deprecated
     */
    public function getList(): array;

    /**
     * Returns the count of all added elements
     */
    public function count(): int;

    /**
     * Erase the map elements
     */
    public function flush(): void;

}
