<?php
declare(strict_types=1);

namespace Database\Adapters\Reader;


use Configuration\Attribute\Configuration;
use Configuration\Attribute\Value;
use Database\Adapters\ConnectionInterface;

#[Configuration]
class ConnectionConfig implements ConnectionInterface
{
    #[Value('dataSource.reader.password')]
    private string $password;

    #[Value('dataSource.reader.user')]
    private string $user;

    #[Value('dataSource.reader.database')]
    private string $database;

    #[Value('dataSource.reader.host')]
    private string $host;

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getDatabase(): string
    {
        return $this->database;
    }

    public function getHost(): string
    {
        return $this->host;
    }
}
