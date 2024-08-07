<?php

namespace FpDbTest\Interface;

interface TokenizerInterface
{
    public function tokenize(string $query): array;
}