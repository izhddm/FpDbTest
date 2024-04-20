<?php

namespace FpDbTest;

use FpDbTest\Factory\ParameterHandlerFactory;
use FpDbTest\Interface\QueryParserInterface;
use FpDbTest\Traits\QueryConstantTrait;
use mysqli;

class QueryParser implements QueryParserInterface
{
    use QueryConstantTrait;

    private const string REGEX = '/(\?([a-z#]|))|({[^{}]*})/';

    public static function parse(string $query, float $skip, mysqli $mysqli, array $args = []): string
    {
        $offset = 0;

        return preg_replace_callback(self::REGEX, function ($match) use ($args, &$offset, $skip, &$mysqli) {
            return self::replaceCallback($match, $offset, $skip, $args, $mysqli);
        }, $query);
    }

    private static function replaceCallback(array $match, int &$offset, float $skip, array $args, mysqli $mysqli): string
    {
        $value = $match[0];

        if (str_starts_with($value, self::PLACEHOLDER_SIGN)) {
            return self::handleReplacement($value, $args, $offset, $mysqli);
        }

        if (str_starts_with($value, self::CONDITIONAL_BLOCK_BEGIN)) {
            return self::handleConditionalBlock($value, $args, $offset, $skip, $mysqli);
        }

        return $value;
    }

    private static function handleReplacement(string $value, array $args, int &$offset, mysqli $mysqli): string
    {
        $handler = ParameterHandlerFactory::create($value, $mysqli);
        $value = $args[$offset++] ?? null;

        return $handler->handle($value);
    }

    private static function handleConditionalBlock(string $value, array $args, int &$offset, float $skip, mysqli $mysqli): string
    {
        $countArgs = substr_count($value, self::PLACEHOLDER_SIGN);
        $subArgs = array_slice($args, $offset, $countArgs, true);

        if (in_array($skip, $subArgs, true)) {
            $offset += $countArgs;

            return '';
        }

        $blockContent = trim($value, self::CONDITIONAL_BLOCK_BEGIN.self::CONDITIONAL_BLOCK_END);

        return preg_replace_callback(self::REGEX, function ($match) use ($args, &$offset, $skip, &$mysqli) {
            return self::replaceCallback($match, $offset, $skip, $args, $mysqli);
        }, $blockContent);
    }
}
