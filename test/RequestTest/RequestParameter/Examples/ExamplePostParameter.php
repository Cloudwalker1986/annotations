<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter\Examples;

class ExamplePostParameter {
    public function __construct(
        private string $parameterOne,
        private string $parameterTwo
    ) {}

    public function getParameterOne(): string
    {
        return $this->parameterOne;
    }

    public function getParameterTwo(): string
    {
        return $this->parameterTwo;
    }
}
