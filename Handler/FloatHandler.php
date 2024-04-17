<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;

class FloatHandler implements ParameterHandlerInterface
{
    public function handle($value): float
    {
        if (is_null($value)) {
            return (new NullHandler())->handle($value);
        }

        return floatval($value);
    }
}