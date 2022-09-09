<?php
declare(strict_types=1);

namespace Event\DataObject;

final class EventDispatch
{
    public function __construct(private readonly string $listener, private readonly string $method){}

    public function getListener(): string
    {
        return $this->listener;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
