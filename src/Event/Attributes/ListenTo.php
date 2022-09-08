<?php
declare(strict_types=1);

namespace Event\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class ListenTo
{
    public function __construct(private readonly string $eventName, private readonly string $listener) {}

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getListener(): string
    {
        return $this->listener;
    }
}
