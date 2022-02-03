<?php
declare(strict_types=1);

namespace Configuration\Attribute;

use Attribute;
use InvalidArgumentException;
use Utils\Collection;
use Utils\Map;
use function PHPUnit\Framework\exactly;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS)]
class Configuration
{

}
