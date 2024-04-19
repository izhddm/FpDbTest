<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;

class IntHandler implements ParameterHandlerInterface
{
    public function handle($value): int|string
    {
        if (is_null($value)) {
            return (new NullHandler())->handle($value);
        }

        return intval($value);
    }
}