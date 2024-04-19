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

    private mysqli $mysqli;

    public function __construct(mysqli $mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function buildQuery(string $query, array $args = []): string|false
    {
        if (QueryValidator::validate($query, $args)) {
            return QueryParser::parse($query, $this->skip(), $args);
        }

        return false;
    }

    public function skip(): float
    {
        return self::SKIP;
    }
}
