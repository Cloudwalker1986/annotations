<?php
declare(strict_types=1);


namespace Configuration;


use Autowired\Handler\CustomHandlerInterface;
use Configuration\Attribute\Configuration;
use ReflectionClass;

class ConfigurationHandler implements CustomHandlerInterface
{
    public function handle(object $object): void
    {
        $reflection = new ReflectionClass($object);
        foreach ($reflection->getAttributes(Configuration::class) as $configuration) {
            call_user_func(
                [
                    Handler::class,
                    'handle'
                ],
                $object,
                $configuration->newInstance()
            );
        }
    }
}
