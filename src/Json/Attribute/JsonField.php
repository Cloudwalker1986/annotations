<?php
declare(strict_types=1);


namespace Json\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::IS_REPEATABLE)]
class JsonField
{
    public function __construct(private readonly string $alias) {}

    public function getAlias(): string
    {
        return $this->alias;
    }
}
