<?php
declare(strict_types=1);

namespace JsonTest\Example;

use Json\Attribute\JsonField;
use Json\Attribute\JsonSerializable;

#[JsonSerializable]
class ObjectA
{
    private string $fieldOne;

    #[JsonField(alias: 'fieldTwo')]
    private int $fieldTwoThree;

    public function getFieldOne(): string
    {
        return $this->fieldOne;
    }

    public function getFieldTwoThree(): int
    {
        return $this->fieldTwoThree;
    }
}
