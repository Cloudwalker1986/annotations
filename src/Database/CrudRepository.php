<?php
declare(strict_types=1);

namespace Database;

use Database\Attributes\Entity\Enum;
use Database\Attributes\Table\Column;
use Database\Attributes\Table\ErrorCode\ErrorCode;
use Database\Attributes\Table\Exception\MissingPrimaryKeyException;
use Database\Attributes\Table\PrimaryKey;
use ReflectionClass;
use ReflectionEnum;

class CrudRepository extends BaseRepository implements CrudRepositoryInterface
{
    /**
     * @throws \ReflectionException
     */
    public function persists(EntityInterface $entity): EntityInterface
    {
        $repository = $this->getRepositoryAttribute();

        $data = $this->convertToDbColumnKeyMap($entity);
        $quotedKeys = [];
        $bindParams = [];

        foreach ($data as $key => $value) {
            $quotedKeys[] = sprintf('`%s`', $key);
            $bindParams[] = sprintf(':%s', $key);
        }

        $query = sprintf('INSERT INTO `%s` (%s) VALUES (%s) ', $repository->getTable(), implode(',', $quotedKeys), implode(',', $bindParams),);

        $primaryKeyValue = $this->getWriterAdapter()->persists($query, $data);

        $primaryKey = $this->getPrimaryKey($entity);

        $entityReflection = new ReflectionClass($entity);

        $clonedEntity = clone $entity;

        $entityReflection->getProperty($primaryKey)->setValue($clonedEntity, $primaryKeyValue);

        return $clonedEntity;
    }

    public function delete(EntityInterface $entity): bool
    {
        $entityReflection = new ReflectionClass($entity);

        $repository = $this->getRepositoryAttribute();

        $primaryKey = null;

        foreach ($entityReflection->getProperties() as $property) {
            $primaryKey = $property->getAttributes(PrimaryKey::class);

            if (empty($primaryKey)) {
                continue;
            }

            $value = $property->getValue($entity);

            $dbColumn = $property->getName();

            /** @var \ReflectionAttribute $columnAttr */
            $attributes = $property->getAttributes(Column::class);
            $columnAttr = reset($attributes);

            if (!empty($columnAttr)) {
                /** @var Column $column */
                $column = $columnAttr->newInstance();
                $dbColumn = $column->getColumn();
            }
            $query = sprintf(
                'DELETE FROM %1$s WHERE %2$s = :%2$s',
                $repository->getTable(),
                $dbColumn
            );

            $this->getWriterAdapter()->delete($query, [$dbColumn => $value]);
            break;
        }

        if (empty($primaryKey)) {
            throw new MissingPrimaryKeyException(
                sprintf(
                    'Entity "%s" has no defined primary key attribute',
                    $entityReflection->getName()
                ),
                ErrorCode::MISSING_PRIMARY_KEY->value
            );
        }

        return true;
    }

    private function getPrimaryKey(EntityInterface $entity): string
    {
        $entityReflection = new ReflectionClass($entity);

        foreach ($entityReflection->getProperties() as $property) {
            $primaryKey = $property->getAttributes(PrimaryKey::class);

            if (empty($primaryKey)) {
                continue;
            }

            return $property->getName();
        }

        throw new MissingPrimaryKeyException();
    }

    private function convertToDbColumnKeyMap(EntityInterface $entity): array
    {
        $entityReflection = new ReflectionClass($entity);

        $data = [];

        foreach ($entityReflection->getProperties() as $reflectionProperty) {
            $value = $reflectionProperty->getValue($entity);
            if (empty($value)) {
                continue;
            }

            $columns = $reflectionProperty->getAttributes(Column::class);
            $key = $reflectionProperty->getName();

            if (!empty($columns)) {
                /** @var Column $column */
                $column = $columns[0]->newInstance();

                $key = $column->getColumn();
            }

            $enum = $reflectionProperty->getAttributes(Enum::class)[0] ?? [];

            if (is_object($value) && (!empty($enum) || enum_exists($value))) {
                /** @var ReflectionEnum $enumReflection */
                $enumReflection = new ReflectionEnum($reflectionProperty->getValue($entity));
                $val = $enumReflection->getProperty('value')->getValue($reflectionProperty->getValue($entity));
                $data[$key] = $val;
            } else {
                $data[$key] = $value;
            }
        }
        return $data;
    }
}
