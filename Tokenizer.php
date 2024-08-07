<?php

namespace FpDbTest;

use FpDbTest\Interface\TokenizerInterface;

class Tokenizer implements TokenizerInterface
{
    public function tokenize(string $query): array
    {
        $tokens = [];
        $currentToken = '';
        $inQuotes = false;
        $inBackticks = false;
        $inBlock = false;
        $blockContent = '';
        $braceCount = 0;

        for ($i = 0; $i < strlen($query); $i++) {
            $char = $query[$i];

            if ($char === "'" && !$inBackticks) {
                $inQuotes = !$inQuotes;
                $currentToken .= $char;
            } elseif ($char === '"' && !$inQuotes) {
                $inBackticks = !$inBackticks;
                $currentToken .= $char;
            } elseif ($char === '{' && !$inQuotes && !$inBackticks) {
                if ($inBlock) {
                    $blockContent .= $char;
                    $braceCount++;
                } else {
                    if (!empty($currentToken)) {
                        $tokens[] = $currentToken;
                        $currentToken = '';
                    }
                    $inBlock = true;
                    $braceCount = 1;
                    $blockContent = $char;
                }
            } elseif ($char === '}' && !$inQuotes && !$inBackticks) {
                if ($inBlock) {
                    $blockContent .= $char;
                    $braceCount--;
                    if ($braceCount === 0) {
                        $inBlock = false;
                        $tokens[] = $blockContent;
                        $blockContent = '';
                    }
                }
            } elseif ($char === ' ' && !$inQuotes && !$inBackticks && !$inBlock) {
                if (!empty($currentToken)) {
                    $tokens[] = $currentToken;
                    $currentToken = '';
                }
            } else {
                if ($inBlock) {
                    $blockContent .= $char;
                } else {
                    $currentToken .= $char;
                }
            }
        }
        if (!empty($currentToken)) {
            $tokens[] = $currentToken;
        }

        return $tokens;
    }
}