<?php
declare(strict_types=1);

namespace Database\Parameters;

abstract class AbstractSearch
{
    private array $parameters;

    public function add(string $key, string|array|int|float|null $value): AbstractSearch
    {
        $this->parameters[$key] = $value;

        return $this;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}