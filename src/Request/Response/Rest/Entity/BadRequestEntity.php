<?php
declare(strict_types=1);

namespace Request\Response\Rest\Entity;

use Utils\Map;

class BadRequestEntity implements Entity
{
    public function __construct(private readonly string $errorMessage, private readonly ?Map $errorFields = null) {}

    public function getErrorMessage(): string
    {
        return $this->errorMessage;
    }

    public function getErrorFields(): ?Map
    {
        return $this->errorFields;
    }
}
