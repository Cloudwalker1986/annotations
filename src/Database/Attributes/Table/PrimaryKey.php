<?php
declare(strict_types=1);


namespace Database\Attributes\Table;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::IS_REPEATABLE)]
class PrimaryKey
{
}
