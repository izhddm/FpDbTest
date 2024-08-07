<?php

namespace FpDbTest;

use FpDbTest\Interface\SqlBuilderInterface;
use KeywordTraits;

class SqlBuilder implements SqlBuilderInterface
{
    use KeywordTraits;

    public function buildSql(array $ast): string
    {
        $sql = '';

        foreach ($ast as $clause => $expressions) {
            if (in_array($clause, $this->keywords)) {
                $sql .= $clause.' ';
                foreach ($expressions as $expr) {
                    if (isset($expr['CONDITION'])) {
                        $expr[] = implode(' ', $expr['CONDITION']);
                        unset($expr['CONDITION']);
                    }
                    $sql .= implode(' ', $expr);
                    $sql .= ' ';
                }
            }
        }

        return trim($sql);
    }
}