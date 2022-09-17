<?php
declare(strict_types=1);

namespace JsonTest\Example;

class ObjectD
{
    private string $fieldOneThree;

    public function getFieldOne(): string
    {
        return $this->fieldOneThree;
    }
}
