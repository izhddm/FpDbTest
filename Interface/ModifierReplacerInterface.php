<?php

namespace FpDbTest\Interface;

use mysqli;

interface ModifierReplacerInterface
{
    public function replace(array $ast, array $modifiers, mysqli $mysqli): array;
}