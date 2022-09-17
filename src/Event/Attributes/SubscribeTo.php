<?php
declare(strict_types=1);

namespace Event\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SubscribeTo
{
    public function __construct(private readonly string $eventName, private readonly string $subscriber) {}

    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getSubscriber(): string
    {
        return $this->subscriber;
    }
}
