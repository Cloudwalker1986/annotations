<?php
declare(strict_types=1);

namespace Request\Route;

use Autowired\DependencyContainer;
use ReflectionClass;
use Request\Response\Response;

class Dispatcher
{
    public function __construct(
        private readonly ReflectionClass $class,
        private readonly string $method,
        private readonly  array $parameters,
        private readonly DependencyContainer $container) {}

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
            return call_user_func_array([$this->container->get($this->getClass()->getName()), $this->getMethod()], $this->getParameters());
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}
