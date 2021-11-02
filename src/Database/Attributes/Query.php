<?php
declare(strict_types=1);

namespace Database\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::IS_REPEATABLE)]
class Query
{
    public function __construct(private string $query) {}

    public function getQuery(): string
    {
        return $this->query;
    }
}
