<?php
declare(strict_types=1);

namespace Request\Attributes\Json;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class JsonRequest
{
    public function __construct(
        private readonly ?string $alias = null,
        private readonly ?string $classType = null
    ) {}

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getClassType(): ?string
    {
        return $this->classType;
    }
}
