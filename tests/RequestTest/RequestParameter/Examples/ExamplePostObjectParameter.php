<?php
declare(strict_types=1);

namespace RequestTest\RequestParameter\Examples;

use Request\Attributes\Parameters\PostParameter;

class ExamplePostObjectParameter
{
    #[PostParameter]
    private ?string $parameterOne;

    #[PostParameter]
    private ?string $parameterTwo;

    #[PostParameter('aliasParameter')]
    private ?string $parameterAlias;

    public function getParameterOne(): ?string
    {
        return $this->parameterOne;
    }

    public function getParameterTwo(): ?string
    {
        return $this->parameterTwo;
    }

    public function getParameterAlias(): ?string
    {
        return $this->parameterAlias;
    }
}
