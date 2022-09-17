<?php
declare(strict_types=1);

namespace JsonTest\Example;

use Json\Attribute\CollectionType;
use Json\Attribute\JsonSerializable;
use Utils\Collection;

#[JsonSerializable]
class ObjectC
{
    #[CollectionType(ObjectB::class)]
    private Collection $fieldOneThree;

    private \DateTimeInterface $time;

    public function getFieldOne(): Collection
    {
        return $this->fieldOneThree;
    }

    public function getTime(): \DateTimeInterface
    {
        return $this->time;
    }
}
