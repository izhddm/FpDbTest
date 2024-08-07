<?php

namespace FpDbTest\Interface;

interface SqlBuilderInterface
{
    public function buildSql(array $ast): string;
}