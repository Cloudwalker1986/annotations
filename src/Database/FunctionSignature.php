<?php
declare(strict_types=1);

namespace Database;

final class FunctionSignature
{
    public function __construct(
        private string $methodName,
        private string $returnParam,
        private string $queryValue,
        private string $parameters,
        private string $parameterVariable
    ) {}

    public function getMethodName(): string
    {
        return $this->methodName;
    }

    public function getReturnParam(): string
    {
        return $this->returnParam;
    }

    public function getQueryValue(): string
    {
        return $this->queryValue;
    }

    public function getParameters(): string
    {
        return $this->parameters;
    }

    public function getParameterVariable(): string
    {
        return $this->parameterVariable;
    }
}
