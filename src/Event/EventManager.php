<?php
declare(strict_types=1);

namespace Event;

use Autowired\Autowired;
use Autowired\DependencyContainer;
use Event\DataObject\EventDispatch;
use Event\Exception\NotRegisteredEventException;
use Utils\Collection;
use Utils\HashMap;
use Utils\ListCollection;

class EventManager
{
    #[Autowired(cachingAllowed: false)]
    private HashMap $listenerEventNameMap;

    #[Autowired]
    private DependencyContainer $dependencyContainer;

    public function addListener(string $eventName, EventDispatch $listener): void
    {
        if ($this->listenerEventNameMap->has($eventName)) {
            /** @var Collection $collection */
            $collection = $this->listenerEventNameMap->get($eventName);
            $collection->add($listener);
            return;
        }

        $collection = new ListCollection();
        $collection->add($listener);
        $this->listenerEventNameMap->add($eventName, $collection);
    }

    public function dispatch(string $eventName, PayloadInterface $payload)
    {
        $this->assertEventNameIsRegistered($eventName);

        /** @var Collection $listeners */
        $listeners = $this->listenerEventNameMap->get($eventName);

        /** @var EventDispatch $eventDispatchDataObject */
        foreach ($listeners->getList() as $eventDispatchDataObject) {
            try {
                $listener = $this->dependencyContainer->get($eventDispatchDataObject->getListener());
                $listener->{$eventDispatchDataObject->getMethod()}($payload);
            } catch (\Throwable $e) {
                // for now no handling required
                continue;
            }
        }
    }

    private function assertEventNameIsRegistered(string $eventName)
    {
        if (!$this->listenerEventNameMap->has($eventName)) {
            throw new NotRegisteredEventException($eventName);
        }
    }
}
