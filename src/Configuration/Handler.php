<?php
declare(strict_types=1);

namespace Configuration;

use Configuration\Attribute\Value;

class Handler
{
    public static function handle(object $config, Config $configuration): void
    {
        $reflection = new \ReflectionClass($config);
        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes(Value::class) as $attribute) {

                /** @var Value $value */
                $value = $attribute->newInstance();
                $property->setValue($config, $configuration->getValueByPath($value));
            }
        }
    }
}
