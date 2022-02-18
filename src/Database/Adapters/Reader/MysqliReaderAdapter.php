<?php
declare(strict_types=1);

namespace Database\Adapters\Reader;
use Database\Adapters\AbstractAdapter;
use PDO;

class MysqliReaderAdapter extends AbstractAdapter implements ReaderAdapterInterface
{
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
