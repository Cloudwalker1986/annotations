<?php
declare(strict_types=1);

namespace EventTest\Example;

use Event\Attributes\ListenTo;
use Event\PayloadInterface;

class ListenerExampleB
{
    #[ListenTo(EventOnCreated::ON_EXAMPLE_CREATED, __CLASS__)]
    public function totallyDifferentNam(PayloadInterface $payload): void
    {
        $payload->increase();
    }
}
