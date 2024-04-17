<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;

class StringHandler implements ParameterHandlerInterface
{
    public function handle($value): string
    {
        return "'".addslashes($value)."'";
    }
}