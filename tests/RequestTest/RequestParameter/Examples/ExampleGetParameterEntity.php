<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter\Examples;

use Request\Attributes\Json\JsonResponse;
use Request\Response\Rest\Entity\Entity;

class ExampleGetParameterEntity implements Entity {
    public function __construct(
        #[JsonResponse] private string $firstGetParameter) {}
    public function getAnyParameter(): string
    {
        return $this->firstGetParameter;
    }
}
