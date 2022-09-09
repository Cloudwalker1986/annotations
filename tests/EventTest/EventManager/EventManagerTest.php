<?php
declare(strict_types=1);

namespace EventTest\EventManager;

use Autowired\DependencyContainer;
use Event\Subscriber\Resolver;
use EventTest\Example\ExampleService;
use EventTest\Example\Payload;
use PHPUnit\Framework\TestCase;

class EventManagerTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        $eventListenerResolver = DependencyContainer::getInstance()->get(Resolver::class);
        $eventListenerResolver->resolve(
            __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '/..' . DIRECTORY_SEPARATOR . '/EventTest'
        );
    }

    /**
     * @test
     */
    public function dispatch(): void
    {
        $payload = new Payload(0);
        $service = DependencyContainer::getInstance()->get(ExampleService::class);
        $service->doSomeAction($payload);

        $this->assertEquals(2, $payload->getCount());
    }
}
