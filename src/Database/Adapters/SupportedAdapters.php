<?php
declare(strict_types=1);

namespace Database\Adapters;

enum SupportedAdapters: string
{
    case PDO = 'pdo';
    case MYSQLI = 'mysqli';

    public static function isPdo(AdapterConfig $config): bool
    {
        return self::PDO->value === $config->getAdapterType();
    }

    public static function isMysqli(AdapterConfig $config): bool
    {
        return self::MYSQLI->value === $config->getAdapterType();
    }

    public static function getAdapterTypes(): string
    {
        return implode(',', self::cases());
    }
}
