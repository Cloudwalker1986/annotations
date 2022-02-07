<?php
declare(strict_types=1);

namespace Database\Adapters;

use Autowired\Attribute\AfterConstruct;
use Autowired\Autowired;
use Database\Adapters\Reader\ConnectionConfig;
use Database\Parameters;
use PDO;
use PDOStatement;
use RuntimeException;


class AbstractAdapter
{
    #[Autowired(concreteClass: ConnectionConfig::class)]
    protected ConnectionInterface $config;

    private null|PDO $connection = null;

    #[AfterConstruct]
    public function init(): void
    {
        $this->connection = new PDO(
            sprintf('mysql:dbname=%s;host=%s', $this->config->getDatabase(), $this->config->getHost()),
            $this->config->getUser(),
            $this->config->getPassword()
        );
    }

    public function getConnection(): null|PDO
    {
        return $this->connection;
    }
    protected function getPreparedStatement(string $query, array $bindingParameters): PDOStatement
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

    protected function bindParameters(
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

    protected function applyPagination(array $bindingParameters, string $query): string
    {
        foreach ($bindingParameters as $value) {
            if ($value instanceof Parameters\Pagination) {
                $query .= sprintf(' LIMIT %d OFFSET %d', $value->getLimit(), $value->getOffset());
            }
        }

        return $query;
    }
}
