<?php
namespace FpDbTest;

use FpDbTest\Factory\ParameterHandlerFactory;
use FpDbTest\Interface\QueryParserInterface;

class QueryParser implements QueryParserInterface
{
    private const string PLACEHOLDER_SIGN = '?';
    private const string CONDITIONAL_BLOCK_BEGIN = '{';
    private const string CONDITIONAL_BLOCK_END = '}';
    private const string REGEX = '/(\?([a-z#]|))|({[^{}]*})/';

    public static function parse(string $query, float $skip, array $args = []): string
    {
        $offset = 0;

        return preg_replace_callback(self::REGEX, function ($match) use ($args, &$offset, $skip) {
            return self::replaceCallback($match, $offset, $skip, $args);
        }, $query);
    }

    private static function replaceCallback(array $match, int &$offset, float $skip, array $args = []): string
    {
        $value = $match[0];

        if (str_starts_with($value, self::PLACEHOLDER_SIGN)) {
            return self::handleReplacement($value, $args, $offset);
        }

        if (str_starts_with($value, self::CONDITIONAL_BLOCK_BEGIN)) {
            return self::handleConditionalBlock($value, $args, $offset, $skip);
        }

        return $value;
    }

    private static function handleReplacement(string $value, array $args, int &$offset): string
    {
        $handler = ParameterHandlerFactory::create($value);
        $value = $args[$offset++] ?? null;

        return $handler->handle($value);
    }

    private static function handleConditionalBlock(string $value, array $args, int &$offset, float $skip): string
    {
        $countArgs = substr_count($value, self::PLACEHOLDER_SIGN);
        $subArgs = array_slice($args, $offset, $countArgs, true);

        if (in_array($skip, $subArgs, true)) {
            $offset += $countArgs;
            return '';
        }

        $blockContent = trim($value, self::CONDITIONAL_BLOCK_BEGIN.self::CONDITIONAL_BLOCK_END);

        return preg_replace_callback(self::REGEX, function ($match) use ($args, &$offset, $skip) {
            return self::replaceCallback($match, $offset, $skip, $args);
        }, $blockContent);
    }
}
