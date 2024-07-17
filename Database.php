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
            list($normalizedQuery, $normalizedArgs) = QueryNormalizer::normalize($query, $args);

            return QueryParser::parse($normalizedQuery, $this->skip(), $this->mysqli, $normalizedArgs);
        }

        return false;
    }

    public function skip(): float
    {
        return self::SKIP;
    }
}
