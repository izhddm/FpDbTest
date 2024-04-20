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

        $isList = array_is_list($value);
        $result = [];

        foreach ($value as $key => $v) {
            if ($isList) {
                $result[] = (new DefaultHandler($this->mysqli))->handle($v);
            } else {
                $result[] = (new IdentifierHandler())->handle($key).' = '.(new DefaultHandler($this->mysqli))->handle($v);
            }
        }

        return implode(', ', $result);
    }
}