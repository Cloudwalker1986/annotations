<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter\Examples;

use Request\Response\Rest\Entity;

class ExampleGetParametersEntity implements Entity
{
    public function __construct(private string $parameterOne, private string $parameterTwo){}

    public function getParameterOne(): string
    {
        return $this->parameterOne;
    }

    public function getParameterTwo(): string
    {
        return $this->parameterTwo;
    }
}
