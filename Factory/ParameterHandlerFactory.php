<?php

namespace FpDbTest\Factory;

use FpDbTest\Handler\ArrayHandler;
use FpDbTest\Handler\DefaultHandler;
use FpDbTest\Handler\FloatHandler;
use FpDbTest\Handler\IdentifierHandler;
use FpDbTest\Handler\IntHandler;
use InvalidArgumentException;
use mysqli;

class ParameterHandlerFactory
{
    public static function create(string $specifier, mysqli $mysqli): IntHandler|FloatHandler|ArrayHandler|DefaultHandler|IdentifierHandler
    {
        // TODO: Пересмотреть эту фабрику для избавления от ручного редактирования при добавлении новых обработчиков. Извлекать спецификатор, связанный с обработчиком, из атрибутов.
        return match ($specifier) {
            '?d' => new IntHandler(),
            '?f' => new FloatHandler(),
            '?a' => new ArrayHandler($mysqli),
            '?#' => new IdentifierHandler(),
            '?' => new DefaultHandler($mysqli),
            default => throw new InvalidArgumentException('Unsupported specifier: '.$specifier),
        };
    }
}