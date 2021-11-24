<?php
declare(strict_types=1);


namespace RequestTest\RequestParameter\Examples;


use Request\Response\Rest\Entity\Entity;

class ExamplePostObjectParameterEntity implements Entity
{
    public function __construct(private ?string $one = null, private ?string $two = null, private ?string $three = null) {}

    public function getOne(): ?string
    {
        return $this->one;
    }

    public function getTwo(): ?string
    {
        return $this->two;
    }

    public function getThree(): ?string
    {
        return $this->three;
    }
}