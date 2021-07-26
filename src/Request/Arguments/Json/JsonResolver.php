<?php
declare(strict_types=1);

namespace Request\Arguments\Json;

use ReflectionProperty;
use Request\Attributes\Json\JsonRequest;
use Utils\Collection;
use Utils\HasMap;
use Utils\ListCollection;
use Utils\Map;

class JsonResolver
{
    public function resolveObject(
        JsonRequest $jsonRequest,
        ReflectionProperty $property,
        array $params,
        callable $callback,
        object $rootObject
    ): object
    {
        $type = $jsonRequest->getClassType();
        $val = null;

        if ($property->getType()->getName() === Collection::class) {
            $val = new ListCollection();
            foreach ($params[$jsonRequest->getAlias() ?? $property->getName()] as $value) {
                $callback($type, $value);
                $val->add($callback($type, $value));
            }
        } elseif ($property->getType()->getName() === Map::class) {
            $val = new HasMap();
            foreach ($params[$jsonRequest->getAlias() ?? $property->getName()] as $key =>  $value) {
                $callback($type, $value);
                $val->add($key, $callback($type, $value));
            }
        }
        $property->setAccessible(true);
        $property->setValue($rootObject, $val);

        return $rootObject;
    }

}
