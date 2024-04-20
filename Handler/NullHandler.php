<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;
use InvalidArgumentException;

class  NullHandler implements ParameterHandlerInterface
{
    public function handle($value): string
    {
        if (!is_null($value)) {
            throw new InvalidArgumentException('Expected Null, got '.gettype($value));
        }

        return 'NULL';
    }
}