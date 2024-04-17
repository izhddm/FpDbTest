<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;
use InvalidArgumentException;

class DefaultHandler implements ParameterHandlerInterface
{
    public function handle($value): string
    {
        if (is_string($value)) {
            return (new StringHandler())->handle($value);
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