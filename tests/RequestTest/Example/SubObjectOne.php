<?php
declare(strict_types=1);

namespace RequestTest\Example;

use Request\Attributes\Json\JsonRequest;

class SubObjectOne
{
    public function __construct(
        #[JsonRequest] private string $subFieldOne = '',
        #[JsonRequest(alias: 'subFieldTwoAlias')] private ?string $subFieldTwo = ''
    )  {
    }

    public function getSubFieldOne(): string
    {
        return $this->subFieldOne;
    }

    public function getSubFieldTwo(): string
    {
        return $this->subFieldTwo;
    }
}
