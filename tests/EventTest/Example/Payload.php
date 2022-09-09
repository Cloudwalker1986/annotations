<?php
declare(strict_types=1);

namespace EventTest\Example;

use Event\PayloadInterface;

class Payload implements PayloadInterface
{
public function __construct(private int $count) {}

    public function getCount(): int
    {
        return $this->count;
    }

    public function increase(): void
    {
        $this->count++;
        
    }
}
