<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;
use InvalidArgumentException;

class BooleanHandler implements ParameterHandlerInterface
{
    public function handle($value): int
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException("Expected Boolean, got ".gettype($value));
        }

        return (int)$value;
    }
}