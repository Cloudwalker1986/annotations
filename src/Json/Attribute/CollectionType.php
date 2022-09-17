<?php
declare(strict_types=1);

namespace Json\Attribute;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PROPERTY)]
class CollectionType
{
    public function __construct(private readonly string $entityType) {}

    public function getEntityType(): string
    {
        return $this->entityType;
    }
}
