<?php

namespace FpDbTest;

use FpDbTest\Interface\AstBuilderInterface;
use FpDbTest\Traits\KeywordTraits;

class AstBuilder implements AstBuilderInterface
{
    use KeywordTraits;

    public function buildAst(array $tokens): array
    {
        $ast = [];
        $currentClause = null;
        $currentExpr = [];

        foreach ($tokens as $token) {
            if (strlen($token) > 1 && $token[0] === '{' && $token[strlen($token) - 1] === '}') {
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