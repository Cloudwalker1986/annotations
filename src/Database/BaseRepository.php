<?php
declare(strict_types=1);

namespace Database;

use Autowired\Autowired;
use Database\Attributes\Repository;
use Database\Attributes\Table\Column;
use Database\Reader\ReaderFactory;
use Database\Reader\ReaderInterface;
use ReflectionClass;
use RuntimeException;
use Utils\Collection;
use Utils\ListCollection;

class BaseRepository
{
    private string $primitiveReturnType;

    #[Autowired(concreteClass: ReaderFactory::class, staticFunction: 'getReader')]
    protected ReaderInterface $reader;

    protected function handleQuerySingleEntity(string $query, array $parameters): ?EntityInterface
    {
        $repository = $this->getRepositoryAttribute();

        $data = $this->reader->fetchRow($this->buildSelect($query, $repository), $parameters);

        $repositoryEntity = $repository->getEntity();

        $entity = new $repositoryEntity();

        return $this->getFilledEntity($entity, $data);
    }

    public function handleQueryMultipleEntities(string $query, array $parameters): Collection
    {
        $repository = $this->getRepositoryAttribute();

        $dbRecords = $this->reader->fetchAll($this->buildSelect($query, $repository), $parameters);

        $repositoryEntity = $repository->getEntity();

        $collection = new ListCollection();

        foreach ($dbRecords as $data) {
            $entity = new $repositoryEntity();
            $this->getFilledEntity($entity, $data);
            $collection->add($entity);
        }
        return $collection;
    }

    private function getRepositoryAttribute(): Repository
    {
        $reflection = new ReflectionClass($this);

        $usedInterface = $reflection->getInterfaces();
        $repositoryAttribute = reset($usedInterface)->getAttributes(Repository::class)[0] ?? null;

        if ($repositoryAttribute === null) {
            throw new RuntimeException('Invalid repository definition for entity class');
        }

        return $repositoryAttribute->newInstance();
    }

    private function getFilledEntity(EntityInterface $entity, array $data): EntityInterface
    {
        $entityReflection = new ReflectionClass($entity);

        foreach ($entityReflection->getProperties() as $reflectionProperty) {

            $columns = $reflectionProperty->getAttributes(Column::class);
            $key = $reflectionProperty->getName();

            if (!empty($columns)) {
                /** @var Column $column */
                $column = $columns[0]->newInstance();

                $key = $column->getColumn();
            }

            $reflectionProperty->setAccessible(true);
            $reflectionProperty->setValue($entity, $data[$key] ?? null);
        }

        return $entity;
    }

    private function buildSelect(string $query, Repository $repository): string
    {
        $select = '';
        if (!str_contains('SELECT', $query) || empty($query))  {
            $select = sprintf('SELECT %1$s.* FROM %1$s ', '`' . $repository->getTable() . '`');
        }

        return $select . $query;
    }
}
