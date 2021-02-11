<?php
declare(strict_types=1);


namespace RequestTest\RequestParameter\Examples;


use Request\Response\Rest\Entity;

class ExampleUriParameterEntity implements Entity
{
    public function __construct(private int $uriParameter) {}
    public function getUriParameter(): int
    {
        return $this->uriParameter;
    }
}
