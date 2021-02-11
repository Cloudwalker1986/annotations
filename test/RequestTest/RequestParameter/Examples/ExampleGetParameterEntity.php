<?php
declare(strict_types=1);


namespace RequestTest\RequestParameter\Examples;


use Request\Response\Rest\Entity;

class ExampleGetParameterEntity implements Entity {
    public function __construct(private string $firstGetParameter) {}
    public function getAnyParameter(): string
    {
        return $this->firstGetParameter;
    }
}
