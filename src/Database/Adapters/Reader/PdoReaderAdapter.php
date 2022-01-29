<?php
declare(strict_types=1);

namespace Database\Adapters\Reader;
use Autowired\Autowired;
use Database\Adapters\AbstractAdapter;
use Database\Adapters\ConnectionInterface;
use PDO;

class PdoReaderAdapter extends AbstractAdapter implements ReaderAdapterInterface
{
    #[Autowired(concreteClass: ConnectionConfig::class)]
    protected ConnectionInterface $config;

    public function fetchRow(string $query, array $bindingParameters): array
    {
        $stmt = $this->getPreparedStatement($query, $bindingParameters);

        $data = $stmt->fetch(mode: PDO::FETCH_ASSOC);

        if (!$data) {
            return [];
        }

        return $data;
    }

    public function fetchAll(string $query, array $bindingParameters): array
    {
        $stmt = $this->getPreparedStatement($query, $bindingParameters);

        $data = $stmt->fetchAll(mode: PDO::FETCH_ASSOC);

        if (!$data) {
            return [];
        }

        return $data;
    }
}
