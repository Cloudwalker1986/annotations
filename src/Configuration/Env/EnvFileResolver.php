<?php
declare(strict_types=1);

namespace Configuration\Env;

class EnvFileResolver
{
    private static ?EnvFileResolver $instance = null;

    public static function getInstance(): EnvFileResolver
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function resolve(): void
    {
        if (!defined('APPLICATION_ENV_FILE_PATH')) {
            return;
        }

        if (defined('APPLICATION_ENV_FILE_PATH') && !file_exists(APPLICATION_ENV_FILE_PATH)) {
            throw new \RuntimeException(
                'There is .env file located behind the path ' . APPLICATION_ENV_FILE_PATH
            );
        }

        $lines = file(APPLICATION_ENV_FILE_PATH, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            [$name, $value] = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}
