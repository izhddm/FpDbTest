<?php

namespace FpDbTest;

use FpDbTest\Traits\QueryConstantTrait;
use InvalidArgumentException;

class QueryValidator
{
    use QueryConstantTrait;

    private static function countPlaceholder(string $input): int
    {
        $pattern = "/\?(?=(?:[^']*'[^']*')*[^']*$)/";

        preg_match_all($pattern, $input, $matches);

        return count($matches[0]);
    }

    public static function validate(string $query, array $args): bool
    {
        $countPlaceholders = self::countPlaceholder($query);

        $countArgs = count($args);

        if ($countPlaceholders !== $countArgs) {
            throw new InvalidArgumentException(
                sprintf(
                    'The number of placeholders in the query (%d) does not match the number of arguments provided (%d).',
                    $countPlaceholders,
                    $countArgs
                )
            );
        }

        if (!self::validateConditionalBlocks($query)) {
            throw new InvalidArgumentException('The conditional blocks in the query are not properly opened and closed.');
        }

        return true;
    }

    private static function validateConditionalBlocks(string $query): bool
    {
        $stack = [];
        $length = strlen($query);

        $needle = self::CONDITIONAL_BLOCK_BEGIN;

        for ($i = 0; $i < $length; $i++) {
            $char = $query[$i];

            if (($char === self::CONDITIONAL_BLOCK_BEGIN || $char === self::CONDITIONAL_BLOCK_END)) {
                // Символ соответствует нужному нам
                if ($char === $needle) {
                    $stack[] = $char;

                    // Меняем на противоположный
                    $needle = $needle === self::CONDITIONAL_BLOCK_BEGIN ? self::CONDITIONAL_BLOCK_END : self::CONDITIONAL_BLOCK_BEGIN;
                } else {
                    return false;
                }
            }
        }

        return sizeof($stack) % 2 == 0; // фигурные скобки у нас попарно
    }
}
