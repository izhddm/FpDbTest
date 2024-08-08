<?php

namespace FpDbTest;

use FpDbTest\Factory\ParameterHandlerFactory;
use FpDbTest\Interface\AstBuilderInterface;
use FpDbTest\Interface\CommentRemoverInterface;
use FpDbTest\Interface\ModifierReplacerInterface;
use FpDbTest\Interface\SqlBuilderInterface;
use FpDbTest\Interface\TokenizerInterface;
use mysqli;

class SQLParser
{
    private CommentRemoverInterface $commentRemover;
    private TokenizerInterface $tokenizer;
    private AstBuilderInterface $astBuilder;
    private SqlBuilderInterface $sqlBuilder;
    private ModifierReplacerInterface $modifierReplacer;

    public function __construct(
        CommentRemoverInterface $commentRemover,
        TokenizerInterface $tokenizer,
        AstBuilderInterface $astBuilder,
        SqlBuilderInterface $sqlBuilder,
        ModifierReplacerInterface $modifierReplacer
    ) {
        $this->commentRemover = $commentRemover;
        $this->tokenizer = $tokenizer;
        $this->astBuilder = $astBuilder;
        $this->sqlBuilder = $sqlBuilder;
        $this->modifierReplacer = $modifierReplacer;
    }

    public function parse(string $query): array
    {
        $query = $this->commentRemover->removeComments($query);
        $query = trim($query);
        $tokens = $this->tokenizer->tokenize($query);

        return $this->astBuilder->buildAst($tokens);
    }

    public function toSQL(array $ast): string
    {
        return $this->sqlBuilder->buildSql($ast);
    }

    public function replaceModifiers(array $ast, array $modifiers, mysqli $mysqli): array
    {
        return $this->modifierReplacer->replace($ast, $modifiers, $mysqli);
    }
}
