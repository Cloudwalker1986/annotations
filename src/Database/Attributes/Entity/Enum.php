<?php
declare(strict_types=1);

namespace Database\Attributes\Entity;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class Enum
{
    public function __construct(private readonly string $className) {}

    public function getEntityWithValue(string|int $value)
    {
        return $this->className::from($value);
    }
}
