<?php
declare(strict_types=1);

namespace Database\Adapters\Writer;

use Autowired\Autowired;
use Database\Adapters\AbstractAdapter;
use Database\Adapters\ConnectionInterface;
use RuntimeException;
use Throwable;

class MysqliWriterAdapter extends AbstractAdapter implements WriterAdapterInterface
{
    /**
     * @throws Throwable
     */
    public function persists(string $query, array $bindParams): int|false
    {
        $transactionActive = false;

        $connection = $this->getConnection();

        if ($connection === null) {
            throw new RuntimeException('');
        }

        if (!$connection->inTransaction()) {
            $transactionActive = true;
            $connection->beginTransaction();
        }
        try {
            $this->getPreparedStatement($query, $bindParams);
            $entityId = (int) $connection->lastInsertId();

            if ($transactionActive) {
                $connection->commit();
            }

            return $entityId;
        } catch (Throwable $e) {
            if ($transactionActive) {
                $connection->rollBack();
            }
            throw $e;
        }
    }

    /**
     * @throws Throwable
     */
    public function delete(string $query, array $bindParams): bool
    {
        $transactionActive = false;

        $connection = $this->getConnection();

        if ($connection === null) {
            throw new RuntimeException('');
        }

        if (!$connection->inTransaction()) {
            $transactionActive = true;
            $connection->beginTransaction();
        }
        try {
            $this->getPreparedStatement($query, $bindParams);

            if ($transactionActive) {
                $connection->commit();
            }

            return true;
        } catch (Throwable $e) {
            if ($transactionActive) {
                $connection->rollBack();
            }
            throw $e;
        }
    }
}
