<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\MysqliRequiredInterface;
use FpDbTest\Interface\ParameterHandlerInterface;
use InvalidArgumentException;
use mysqli;

class DefaultHandler implements ParameterHandlerInterface, MysqliRequiredInterface
{
    public function __construct(protected mysqli $mysqli)
    {
    }

    public function handle($value): string
    {
        return match (true) {
            is_string($value) => (new StringHandler($this->mysqli))->handle($value),
            is_null($value) => (new NullHandler())->handle($value),
            is_bool($value) => (new BooleanHandler())->handle($value),
            is_int($value), is_float($value) => $value,
            default => new InvalidArgumentException('Unsupported value type: '.gettype($value))
        };
    }
}