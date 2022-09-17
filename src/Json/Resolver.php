<?php
declare(strict_types=1);

namespace Json;

use Exception;
use Json\Attribute\JsonSerializable;
use Json\Exception\NotJsonSerializableException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;

class Resolver
{
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function fromJson(array $payload, string|object $entity): object
    {
        if (is_string($entity)) {
            $entityObject = new $entity();
        } else {
            $entityObject = $entity;
        }

        $reflectionClass = new ReflectionClass($entityObject);
        $jsonSerializable = $this->getJsonSerializableAttribute($reflectionClass);

        return $jsonSerializable->fromJson($payload, $entityObject, $reflectionClass);
    }

    private function getJsonSerializableAttribute(ReflectionClass $reflectionClass): JsonSerializable
    {
        $jsonSerializableAttribute = $reflectionClass->getAttributes(JsonSerializable::class);

        if ($jsonSerializableAttribute) {
            return $jsonSerializableAttribute[0]->newInstance();
        }

        throw new NotJsonSerializableException();
    }
}
