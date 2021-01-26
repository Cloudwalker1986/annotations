<?php
declare(strict_types=1);

namespace Request\Response;

interface RestResponseEntity extends ResponseEntity
{
    public function getStatus(): int;

    public function getPayload(): array;
}
