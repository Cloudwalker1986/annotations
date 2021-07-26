<?php
declare(strict_types=1);

namespace Request\Attributes\Parameters;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PARAMETER)]
class PostParameter implements Parameter {
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
