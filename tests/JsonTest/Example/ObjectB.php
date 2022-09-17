<?php
declare(strict_types=1);

namespace JsonTest\Example;

use Json\Attribute\JsonField;
use Json\Attribute\JsonSerializable;

#[JsonSerializable]
class ObjectB
{
    private ObjectA $fieldOne;

    public function getFieldOne(): ObjectA
    {
        return $this->fieldOne;
    }
}
