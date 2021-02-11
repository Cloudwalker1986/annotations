<?php
declare(strict_types=1);

namespace Request\Attributes;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_PARAMETER)]
class GetParameter {}
