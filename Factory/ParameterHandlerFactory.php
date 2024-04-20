<?php

namespace FpDbTest\Factory;

use FpDbTest\Handler\ArrayHandler;
use FpDbTest\Handler\DefaultHandler;
use FpDbTest\Handler\FloatHandler;
use FpDbTest\Handler\IdentifierHandler;
use FpDbTest\Handler\IntHandler;
use InvalidArgumentException;

class ParameterHandlerFactory
{
    public static function create($specifier)
    {
        // TODO: Пересмотреть эту фабрику для избавления от ручного редактирования при добавлении новых обработчиков. Извлекать спецификатор, связанный с обработчиком, из атрибутов.
        return match ($specifier) {
            '?d' => new IntHandler(),
            '?f' => new FloatHandler(),
            '?a' => new ArrayHandler(),
            '?#' => new IdentifierHandler(),
            '?' => new DefaultHandler(),
            default => throw new InvalidArgumentException('Unsupported specifier: '.$specifier),
        };
    }
}