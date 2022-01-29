<?php
declare(strict_types=1);

namespace Configuration;

use Autowired\Handler\CustomHandlerInterface;

class ConfigurationHandler implements CustomHandlerInterface
{
    public function handle(object $object): void
    {
        call_user_func(
            [
                Handler::class,
                'handle'
            ],
            $object,
            Config::getInstance()
        );
    }
}
