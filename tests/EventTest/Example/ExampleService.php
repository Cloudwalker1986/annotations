<?php
declare(strict_types=1);

namespace EventTest\Example;

use Autowired\Autowired;
use Event\EventManager;
use Event\PayloadInterface;

class ExampleService
{
    #[Autowired]
    private EventManager $eventManager;

    public function doSomeAction(PayloadInterface $payload): void
    {
        $this->eventManager->dispatch(EventOnCreated::ON_EXAMPLE_CREATED, $payload);
    }
}
