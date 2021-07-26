<?php
declare(strict_types=1);

namespace RequestTest\JsonResponse\Example;

use Request\Attributes\Json\JsonResponse;
use Request\Response\Rest\Entity\Entity;

class ExampleBigResponseObject implements Entity
{
    #[JsonResponse(alias: 'fieldOne')]
    private string $superLogFieldNameWithAlias;

    #[JsonResponse]
    private string $fieldTwo;

    #[JsonResponse(ignore: true)]
    private string $ignoreMe;

    public function __construct(string $superLogFieldNameWithAlias, string $fieldTwo, string $ignoreMe)
    {
        $this->superLogFieldNameWithAlias = $superLogFieldNameWithAlias;
        $this->fieldTwo = $fieldTwo;
        $this->ignoreMe = $ignoreMe;
    }

    public function getSuperLogFieldNameWithAlias(): string
    {
        return $this->superLogFieldNameWithAlias;
    }

    public function getFieldTwo(): string
    {
        return $this->fieldTwo;
    }

    public function getIgnoreMe(): string
    {
        return $this->ignoreMe;
    }
}
