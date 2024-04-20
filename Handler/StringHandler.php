<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\MysqliRequiredInterface;
use FpDbTest\Interface\ParameterHandlerInterface;
use InvalidArgumentException;
use mysqli;

class StringHandler implements ParameterHandlerInterface, MysqliRequiredInterface
{
    public function __construct(protected mysqli $mysqli)
    {
    }

    public function handle($value): string
    {
        if (!is_string($value)) {
            throw new InvalidArgumentException('Expected String, got '.gettype($value));
        }

        return "'".$this->mysqli->real_escape_string($value)."'";
    }
}