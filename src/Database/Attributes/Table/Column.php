<?php
declare(strict_types=1);

namespace Database\Attributes\Table;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Column
{
    public function __construct(private readonly string $column) {}

    public function getColumn(): string
    {
        return $this->column;
    }
}
