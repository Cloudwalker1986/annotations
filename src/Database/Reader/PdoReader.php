<?php
declare(strict_types=1);

namespace Database\Reader;

use Configuration\AutowiredHandler;
use Autowired\Autowired;
use Database\Parameters;
use PDO;
use PDOStatement;
use RuntimeException;

class PdoReader implements ReaderInterface
{
    use AutowiredHandler;

    private PDO $connection;

    #[Autowired]
    private Config $config;

    public function __construct()
    {
        $this->autowired();
        $this->connection = new PDO(
            sprintf('mysql:dbname=%s;host=%s', $this->config->getDatabase(), $this->config->getHost()),
            $this->config->getUser(),
            $this->config->getPassword()
        );
    }

    public function getConnection(): \PDO
    {
        return $this->connection;
    }

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

    private function getPreparedStatement(string $query, array $bindingParameters): PDOStatement
    {
        if ($this->connection === null) {
            throw new RuntimeException('Connection to database is not established.');
        }

        $query = $this->applyPagination($bindingParameters, $query);

        $stmt = $this->connection->prepare($query);
        $debugQuery = $query;

        $this->bindParameters($bindingParameters, $stmt, $debugQuery);

        $stmt->execute();

        return $stmt;
    }

    private function bindParameters(
        array $bindingParameters,
        bool|PDOStatement $stmt,
        string $debugQuery
    ): void
    {
        foreach ($bindingParameters as $key => $value) {
            if ($value instanceof Parameters\Pagination) {
                continue;
            }
            if ($value instanceof Parameters\LikeSearch) {
                foreach ($value->getParameters() as $k => $v) {
                    $likeVal = '%' . $v . '%';
                    $stmt->bindParam(sprintf(':%s', $k), $likeVal);
                    $debugQuery = str_replace(sprintf(':%s', $k), $likeVal, $debugQuery);
                }
                continue;
            }

            if ($value instanceof Parameters\EqualsSearch) {
                foreach ($value->getParameters() as $k => $v) {
                    $likeVal = $v;
                    $stmt->bindParam(sprintf(':%s', $k), $likeVal);
                    $debugQuery = str_replace(sprintf(':%s', $k), $likeVal, $debugQuery);
                }
                continue;
            }

            if (is_array($value)) {
                $val = implode(',', $value);
            } else {
                $val = $value;
            }
            $stmt->bindParam(sprintf(':%s', $key), $val);
            $debugQuery = str_replace(sprintf(':%s', $key), "'" . $val . "'", $debugQuery);
        }
    }

    private function applyPagination(array $bindingParameters, string $query): string
    {
        foreach ($bindingParameters as $value) {
            if ($value instanceof Parameters\Pagination) {
                $query .= sprintf(' LIMIT %d OFFSET %d', $value->getLimit(), $value->getOffset());
            }
        }

        return $query;
    }
}
