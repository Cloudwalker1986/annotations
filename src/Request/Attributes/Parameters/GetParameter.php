<?php
declare(strict_types=1);

namespace Request\Attributes\Parameters;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_PROPERTY)]
class GetParameter implements Parameter {

    public function __construct(
        private readonly ?string $alias = null,
    ) {}

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function isPost(): bool
    {
        return false;
    }

    public function isGet(): bool
    {
        return true;
    }

    public function isRawBody(): bool
    {
        return false;
    }

}
