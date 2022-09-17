<?php
declare(strict_types=1);

namespace Event\DataObject;

final class EventSubscriber
{
    public function __construct(private readonly string $subscriber, private readonly string $method){}

    public function getSubscriber(): string
    {
        return $this->subscriber;
    }

    public function getMethod(): string
    {
        return $this->method;
    }
}
