<?php
declare(strict_types=1);

namespace Configuration;

use Configuration\Attribute\Configuration;
use ReflectionClass;

trait AutowiredHandler
{
    use \Autowired\AutowiredHandler;

    protected function handleCustomAttributes(object $class): void
    {
        $reflection = new ReflectionClass($class);
        foreach ($reflection->getAttributes(Configuration::class) as $configuration) {
            call_user_func(
                [
                    Handler::class,
                    'handle'
                ],
                $class,
                $configuration->newInstance()
            );
        }
    }
}