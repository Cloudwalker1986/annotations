<?php
declare(strict_types=1);


namespace Json\Attribute;

use Attribute;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionProperty;
use Utils\Collection;
use Utils\HashMap;
use Utils\ListCollection;
use Utils\Map;

#[Attribute(Attribute::TARGET_CLASS | Attribute::IS_REPEATABLE)]
class JsonSerializable
{
    /**
     * @throws Exception
     */
    public function fromJson(array $payload, string|object $entity, ReflectionClass $reflectionClass): object
    {
        foreach ($reflectionClass->getProperties() as $property) {

            $key = null;
            $value = null;

            /** @var ReflectionAttribute $attribute */
            foreach ($property->getAttributes(JsonField::class) as $attr) {
                $attribute = $attr->newInstance();
                if (!empty($attribute->getAlias())) {
                    $key = $attribute->getAlias();
                }
            }

            if ($key === null && isset($payload[$property->getName()])) {
                $key = $property->getName();
            }

            $value = $this->handlePropertyTypes(
                $property,
                $payload[$key] ?? null
            );

            if (!empty($value)) {
                $property->setValue($entity, $value);
            }
        }

        return $entity;
    }

    /**
     * @throws Exception
     */
    private function handlePropertyTypes(ReflectionProperty $property, mixed $value): null|string|int|float|object|bool|array
    {
        $type = $property->getType();
        if (!$type) {
            return $value;
        }
        $propertyTypeName = $type->getName();
        if ($propertyTypeName === DateTimeInterface::class && !empty($value)) {
            $value = new DateTimeImmutable($value);
        }

        if (is_array($value)) {
            if ($propertyTypeName === Map::class) {
                $value = $this->fromJson($value, HashMap::class);
            } else if ($propertyTypeName === Collection::class) {

                foreach ($property->getAttributes(CollectionType::class) as $collectionType) {

                    /** @var CollectionType $type */
                    $type = $collectionType->newInstance();
                    $collection = new ListCollection();


                    foreach ($value as $val) {
                        $item = new ($type->getEntityType())();
                        $collection->add(
                            $this->fromJson(
                                $val,
                                $item,
                                new ReflectionClass($item)
                            )
                        );
                    }
                    $value = $collection;
                }
            } else {
                $item = new $propertyTypeName();
                $value = $this->fromJson($value, $item, new \ReflectionClass($item));
            }
        }

        return $value;

    }

}
