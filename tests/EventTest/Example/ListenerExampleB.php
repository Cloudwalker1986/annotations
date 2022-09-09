<?php
declare(strict_types=1);

namespace EventTest\Example;

use Event\Attributes\SubscribeTo;
use Event\PayloadInterface;

class ListenerExampleB
{
    #[SubscribeTo(EventOnCreated::ON_EXAMPLE_CREATED, __CLASS__)]
    public function totallyDifferentNam(PayloadInterface $payload): void
    {
        $payload->increase();
    }
}
