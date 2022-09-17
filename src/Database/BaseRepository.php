<?php
declare(strict_types=1);

namespace Database;

use Autowired\Autowired;
use Database\Adapters\Reader\ReaderFactory;
use Database\Adapters\Reader\ReaderAdapterInterface;
use Database\Adapters\Writer\WriterAdapterInterface;
use Database\Adapters\Writer\WriterFactory;
use Database\Attributes\Entity\Enum;
use Database\Attributes\Repository;
use Database\Attributes\Table\Column;
use ReflectionClass;
use RuntimeException;
use Utils\Collection;
use Utils\ListCollection;

class BaseRepository
{
    private string $primitiveReturnType;

    #[Autowired(concreteClass: ReaderFactory::class, staticFunction: 'getReaderAdapter')]
    protected ReaderAdapterInterface $readerAdapter;

    #[Autowired(concreteClass: WriterFactory::class, staticFunction: 'getWriterAdapter')]
    protected WriterAdapterInterface $writerAdapter;

    protected function handleQuerySingleEntity(string $query, array $parameters): ?EntityInterface
    {
        $repository = $this->getRepositoryAttribute();

        $data = $this->readerAdapter->fetchRow($this->buildSelect($query, $repository), $parameters);

        $repositoryEntity = $repository->getEntity();

        $entity = new $repositoryEntity();

        return $this->getFilledEntity($entity, $data);
    }

    public function handleQueryMultipleEntities(string $query, array $parameters): Collection
    {
        $repository = $this->getRepositoryAttribute();

        $dbRecords = $this->readerAdapter->fetchAll($this->buildSelect($query, $repository), $parameters);

        $repositoryEntity = $repository->getEntity();

        $collection = new ListCollection();

        foreach ($dbRecords as $data) {
            $entity = new $repositoryEntity();
            $this->getFilledEntity($entity, $data);
            $collection->add($entity);
        }
        return $collection;
    }

    protected function getWriterAdapter(): WriterAdapterInterface
    {
        return $this->writerAdapter;
    }

    protected function getRepositoryAttribute(): Repository
    {
        $reflection = new ReflectionClass($this);

        $interfaces = $reflection->getInterfaces();

        $repositoryAttribute = null;

        foreach ($interfaces as $usedInterface) {
            $repositoryAttribute = $usedInterface->getAttributes(Repository::class)[0] ?? null;
            if ($repositoryAttribute === null) {
                continue;
            }
            break;
        }

        if ($repositoryAttribute === null) {
            throw new RuntimeException('Invalid repository definition for entity class');
        }

        return $repositoryAttribute->newInstance();
    }

    private function getFilledEntity(EntityInterface $entity, array $data): EntityInterface
    {
        if (empty($data)) {
            return $entity;
        }

        $entityReflection = new ReflectionClass($entity);

        foreach ($entityReflection->getProperties() as $reflectionProperty) {

            $columns = $reflectionProperty->getAttributes(Column::class);
            $key = $reflectionProperty->getName();

            if (!empty($columns)) {
                /** @var Column $column */
                $column = $columns[0]->newInstance();

                $key = $column->getColumn();
            }

            $value = $data[$key];

            $enums = $reflectionProperty->getAttributes(Enum::class);
            $enum = reset($enums);

            if (!empty($enum)) {
                $entityEnum = $enum->newInstance();
                $value = $entityEnum->getEntityWithValue($data[$key]);
            }

            $reflectionProperty->setValue($entity, $value ?? null);
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
