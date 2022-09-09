<?php
declare(strict_types=1);

namespace Event;

use Autowired\Autowired;
use Autowired\DependencyContainer;
use Event\DataObject\EventSubscriber;
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

    public function addSubscriber(string $eventName, EventSubscriber $subscriber): void
    {
        if ($this->listenerEventNameMap->has($eventName)) {
            /** @var Collection $collection */
            $collection = $this->listenerEventNameMap->get($eventName);
            $collection->add($subscriber);
            return;
        }

        $collection = new ListCollection();
        $collection->add($subscriber);
        $this->listenerEventNameMap->add($eventName, $collection);
    }

    public function dispatch(string $eventName, PayloadInterface $payload): void
    {
        $this->assertEventNameIsRegistered($eventName);

        /** @var Collection $listeners */
        $listeners = $this->listenerEventNameMap->get($eventName);

        /** @var EventSubscriber $eventDispatchDataObject */
        foreach ($listeners->getList() as $eventDispatchDataObject) {
            try {
                $listener = $this->dependencyContainer->get($eventDispatchDataObject->getSubscriber());
                $listener->{$eventDispatchDataObject->getMethod()}($payload);
            } catch (\Throwable $e) {
                // for now no handling required
                continue;
            }
        }
    }

    private function assertEventNameIsRegistered(string $eventName): void
    {
        if (!$this->listenerEventNameMap->has($eventName)) {
            throw new NotRegisteredEventException($eventName);
        }
    }
}
