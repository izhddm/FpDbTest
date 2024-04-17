<?php

namespace FpDbTest\Interface;

interface DatabaseInterface
{
    public function buildQuery(string $query, array $args = []): string;

    public function skip();
}
