<?php

namespace FpDbTest;

use FpDbTest\Interface\QueryNormalizerInterface;
use FpDbTest\Traits\QueryConstantTrait;

class QueryNormalizer implements QueryNormalizerInterface
{
    use QueryConstantTrait;

    public static function normalize(string $query, array $args): array
    {
        $newArgs = [];
        $offset = 0;

        $normalizedQuery = preg_replace_callback("/('([^']*)')|\?/", function ($matches) use (&$newArgs, &$offset, $args) {
            if ($matches[0] == self::PLACEHOLDER_SIGN) {
                $newArgs[] = $args[$offset];
                $offset++;

                return $matches[0];
            }

            if (preg_match("/('([^']*)')/", $matches[0])) {
                $newArgs[] = $matches[2];

                return self::PLACEHOLDER_SIGN;
            }

            return $matches;

        }, $query);

        return [$normalizedQuery, $newArgs];
    }
}

