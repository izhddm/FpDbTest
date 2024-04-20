<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\MysqliRequiredInterface;
use FpDbTest\Interface\ParameterHandlerInterface;
use mysqli;

class StringHandler implements ParameterHandlerInterface, MysqliRequiredInterface
{
    public function __construct(protected mysqli $mysqli)
    {
    }

    public function handle($value): string
    {
        return "'".$this->mysqli->real_escape_string($value)."'";
    }
}