<?php
declare(strict_types=1);

namespace Database\Attributes\Table\ErrorCode;

enum ErrorCode: int
{
    case MISSING_PRIMARY_KEY = 1;
}
