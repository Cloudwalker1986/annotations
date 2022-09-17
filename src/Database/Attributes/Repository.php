<?php
declare(strict_types=1);

namespace Database\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Repository
{
    public function __construct(private readonly string $table, private readonly string $entity) {}

    public function getTable(): string
    {
        return $this->table;
    }

    public function getEntity(): string
    {
        return $this->entity;
    }
}
