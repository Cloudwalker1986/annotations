<?php
declare(strict_types=1);

namespace RequestTest\Example;

use Request\Attributes\Json\JsonRequest;
use Utils\Collection;
use Utils\ListCollection;
use Utils\Map;

class RootMapObject
{
    #[JsonRequest]
    private string $fieldOne;

    #[JsonRequest('fieldTwoAlias')]
    private string $fieldTwo;

    #[JsonRequest(classType: SubObjectOne::class)]
    private ?Map $items;

    public function __construct(string $fieldOne = '', string $fieldTwo = '', Map $items = null)
    {
        $this->fieldOne = $fieldOne;
        $this->fieldTwo = $fieldTwo;
        $this->items = $items;
    }

    public function getFieldOne(): string
    {
        return $this->fieldOne;
    }

    public function getFieldTwo(): string
    {
        return $this->fieldTwo;
    }

    public function getItems(): ?Map
    {
        return $this->items;
    }
}
