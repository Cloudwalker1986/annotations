<?php
declare(strict_types=1);

namespace Configuration;

use Configuration\Attribute\Configuration;
use Configuration\Attribute\Value;
use Database\Reader\Config;

class Handler
{
    public static function handle(Config $config, Configuration $configuration): void
    {
        $reflection = new \ReflectionClass($config);
        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(Value::class) as $attribute) {

                /** @var Value $value */
                $value = $attribute->newInstance();
                $property->setAccessible(true);
                $property->setValue($config, $configuration->getValueByPath($value));
            }
        }

    }
}
