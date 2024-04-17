<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;

class  NullHandler implements ParameterHandlerInterface
{
    public function handle($value): string
    {
        return 'NULL';
    }
}