<?php
declare(strict_types=1);

namespace Database\Reader;

use Autowired\DependencyContainer;

class ReaderFactory
{
    public static function getReader(): ReaderInterface
    {
        return DependencyContainer::getInstance()->get(PdoReader::class);
    }
}
