<?php
declare(strict_types=1);

namespace Database\Adapters\Writer;

use Configuration\Attribute\Configuration;
use Configuration\Attribute\Value;
use Database\Adapters\ConnectionInterface;

#[Configuration]
class ConnectionConfig implements ConnectionInterface
{
    #[Value('dataSource.writer.password')]
    private string $password;

    #[Value('dataSource.writer.user')]
    private string $user;

    #[Value('dataSource.writer.database')]
    private string $database;

    #[Value('dataSource.writer.host')]
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
