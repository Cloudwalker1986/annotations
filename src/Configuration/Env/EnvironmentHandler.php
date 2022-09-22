<?php
declare(strict_types=1);

namespace Configuration\Env;

use Autowired\Handler\CustomHandlerInterface;
use Configuration\Attribute\Env;

class EnvironmentHandler implements CustomHandlerInterface
{
    public function handle(object $object): void
    {
        EnvFileResolver::getInstance()->resolve();

        $reflection = new \ReflectionClass($object);

        foreach ($reflection->getProperties() as $property) {
            $attribute = $property->getAttributes(Env::class);

            if (empty($attribute)) {
                continue;
            }

            /** @var Env $env */
            $env = $attribute[0]->newInstance();

            $value = trim(trim($env->getValue(), '"'), "'");

            $type = $property->getType()->getName();
            if ($type === 'int') {
                $value = (int) $value;
            } elseif ($type === 'bool') {
                $value = (bool) $value;
            } elseif ($type === 'float') {
                $value = (float) $value;
            } elseif ($type === 'array') {
                $value = explode(',', $value);
            }

            $property->setValue($object, $value);

        }
    }
}
