<?php
declare(strict_types=1);

namespace EventTest\Example;

use Event\Attributes\ListenTo;
use Event\PayloadInterface;

class ListenerExampleA
{
    #[ListenTo(EventOnCreated::ON_EXAMPLE_CREATED, __CLASS__)]
    public function someFunctionName(PayloadInterface $payload): void
    {
        $payload->increase();
    }
}
