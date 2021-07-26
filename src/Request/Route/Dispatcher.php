<?php
declare(strict_types=1);

namespace Request\Route;

use ReflectionClass;
use Request\Response\Response;

class Dispatcher
{
    public function __construct(private ReflectionClass $class, private string $method, private array $parameters) {}

    public function getClass(): ReflectionClass
    {
        return $this->class;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function dispatch(): ?Response
    {
        try {
            return call_user_func_array([$this->getClass()->newInstance(), $this->getMethod()], $this->getParameters());
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}
