<?php
declare(strict_types=1);

namespace Request\Attributes\Parameters;

interface Parameter
{
    public function isPost(): bool;

    public function isGet(): bool;

    public function isRawBody(): bool;
}