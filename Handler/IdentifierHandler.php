<?php

namespace FpDbTest\Handler;

use FpDbTest\Interface\ParameterHandlerInterface;

class IdentifierHandler implements ParameterHandlerInterface
{
    public function handle($value): string
    {
        if (is_array($value)) {
            return implode(", ", array_map([$this, 'escapeIdentifier'], $value));
        }

        return $this->escapeIdentifier($value);
    }

    // Экранирование идентификатора для использования в SQL
    private function escapeIdentifier(string $identifier): string
    {
        return "`".str_replace("`", "``", $identifier)."`";
    }
}