<?php
declare(strict_types=1);


namespace Request\Attributes\Json;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class JsonResponse
{
    public function __construct(
        private readonly ?string $alias = '',
        private readonly ?bool $ignore = false
    ) {}

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function shouldIgnore(): ?bool
    {
        return $this->ignore;
    }
}
