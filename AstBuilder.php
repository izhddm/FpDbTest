<?php

namespace FpDbTest;

use FpDbTest\Interface\AstBuilderInterface;
use KeywordTraits;

class AstBuilder implements AstBuilderInterface
{
    use KeywordTraits;

    public function buildAst(array $tokens): array
    {
        $ast = [];
        $currentClause = null;
        $currentExpr = [];

        foreach ($tokens as $token) {
            if (preg_match('/^\{.*\}$/', $token)) {
                $blockContent = trim($token, '{}');
                $blockTokens = (new Tokenizer())->tokenize($blockContent);
                $currentExpr['CONDITION'] = $blockTokens;
            } else {
                $upperToken = strtoupper($token);

                if (in_array($upperToken, $this->keywords)) {
                    if ($currentClause) {
                        if (!empty($currentExpr)) {
                            $ast[$currentClause][] = $currentExpr;
                        }
                        $currentExpr = [];
                    }
                    $currentClause = $upperToken;
                    if (!isset($ast[$currentClause])) {
                        $ast[$currentClause] = [];
                    }
                } else {
                    $currentExpr[] = $token;
                }
            }
        }
        if ($currentClause) {
            if (!empty($currentExpr)) {
                $ast[$currentClause][] = $currentExpr;
            }
        }

        return $ast;
    }
}