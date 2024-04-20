<?php

namespace FpDbTest;

use FpDbTest\Interface\DatabaseInterface;
use mysqli;

/**
 * Класс Database, класс взаимодействия с базой данных.
 */
class Database implements DatabaseInterface
{
    private const float SKIP = INF;

    public function __construct(protected mysqli $mysqli)
    {
    }

    public function buildQuery(string $query, array $args = []): string|false
    {
        if (QueryValidator::validate($query, $args)) {
            return QueryParser::parse($query, $this->skip(), $this->mysqli, $args);
        }

        return false;
    }

    public function skip(): float
    {
        return self::SKIP;
    }
}
