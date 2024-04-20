<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;

class StringHandler implements ParameterHandlerInterface
{
    public function handle($value): string
    {
        // TODO: Использовать mysqli_real_escape_string вместо addslashes
        return "'".addslashes($value)."'";
    }
}