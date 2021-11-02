<?php
declare(strict_types=1);

namespace Database\Reader;

class ReaderFactory
{
    public static function getReader(): ReaderInterface
    {
        return new PdoReader();
    }
}
