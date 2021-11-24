<?php
declare(strict_types=1);

namespace Request\Attributes\Parameters;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
class PostParameter implements Parameter {

    public function __construct(
        private ?string $alias = null,
    ) {}

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function isPost(): bool
    {
        return true;
    }

    public function isGet(): bool
    {
        return false;
    }

    public function isRawBody(): bool
    {
        return false;
    }
}
