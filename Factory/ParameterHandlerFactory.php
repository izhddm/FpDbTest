<?php

namespace FpDbTest\Factory;

use FpDbTest\Handler\ArrayHandler;
use FpDbTest\Handler\DefaultHandler;
use FpDbTest\Handler\FloatHandler;
use FpDbTest\Handler\IdentifierHandler;
use FpDbTest\Handler\IntHandler;
use FpDbTest\Handler\StringHandler;
use InvalidArgumentException;

class ParameterHandlerFactory
{
    public static function create($specifier)
    {
        return match ($specifier) {
            '?d' => new IntHandler(),
            '?f' => new FloatHandler(),
            '?s' => new StringHandler(),
            '?a' => new ArrayHandler(),
            '?#' => new IdentifierHandler(),
            '?' => new DefaultHandler(),
            default => throw new InvalidArgumentException('Unsupported specifier: '.$specifier),
        };
    }
}