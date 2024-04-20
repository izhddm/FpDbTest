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
        if (is_string($value)) {
            return (new StringHandler($this->mysqli))->handle($value);
        }

        if (is_null($value)) {
            return (new NullHandler())->handle($value);
        }

        if (is_bool($value)) {
            return (new BooleanHandler())->handle($value);
        }

        if (is_int($value) || is_float($value)) {
            return $value;
        }

        // Если тип не соответствует ни одному из допустимых, выбрасываем исключение
        throw new InvalidArgumentException("Unsupported value type: ".gettype($value));
    }
}