<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\MysqliRequiredInterface;
use FpDbTest\Interface\ParameterHandlerInterface;
use InvalidArgumentException;
use mysqli;

class ArrayHandler implements ParameterHandlerInterface, MysqliRequiredInterface
{
    public function __construct(protected mysqli $mysqli)
    {
    }

    public function handle($value): string
    {
        if (!is_array($value)) {
            throw new InvalidArgumentException('Expected Array, got '.gettype($value));
        }

        $isAssociativeArray = $this->isAssociativeArray($value);
        $result = [];

        foreach ($value as $key => $v) {
            if ($isAssociativeArray) {
                $result[] = (new IdentifierHandler())->handle($key).' = '.(new DefaultHandler($this->mysqli))->handle($v);
            } else {
                $result[] = (new DefaultHandler($this->mysqli))->handle($v);
            }
        }

        return implode(', ', $result);
    }

    private function isAssociativeArray(array $array): bool
    {
        $expectedKey = 0;
        foreach ($array as $key => $value) {
            if ($key !== $expectedKey++) {
                return true; // Если хотя бы один ключ не соответствует ожидаемому, массив ассоциативный
            }
        }

        return false; // Если все ключи соответствуют ожидаемым значениям, массив не ассоциативный
    }
}