<?php
declare(strict_types=1);

namespace Event\Exception;

class NotRegisteredEventException extends \InvalidArgumentException
{
    private const UNREGISTERED_EVENT_NAME = 'The dispatched event "%s" is not registered';

    public function __construct(string $eventName)
    {
        parent::__construct(
            sprintf(
                static::UNREGISTERED_EVENT_NAME,
                $eventName
            )
        );
    }
}
