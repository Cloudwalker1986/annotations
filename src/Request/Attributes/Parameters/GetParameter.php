<?php
declare(strict_types=1);

namespace Request\Attributes\Parameters;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PARAMETER)]
class GetParameter implements Parameter {
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
