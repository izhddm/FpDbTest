<?php

namespace FpDbTest;

use FpDbTest\Interface\AstBuilderInterface;
use FpDbTest\Interface\CommentRemoverInterface;
use FpDbTest\Interface\SqlBuilderInterface;
use FpDbTest\Interface\TokenizerInterface;

class SQLParser
{
    private CommentRemoverInterface $commentRemover;
    private TokenizerInterface $tokenizer;
    private AstBuilderInterface $astBuilder;
    private SqlBuilderInterface $sqlBuilder;

    public function __construct(
        CommentRemoverInterface $commentRemover,
        TokenizerInterface $tokenizer,
        AstBuilderInterface $astBuilder,
        SqlBuilderInterface $sqlBuilder
    ) {
        $this->commentRemover = $commentRemover;
        $this->tokenizer = $tokenizer;
        $this->astBuilder = $astBuilder;
        $this->sqlBuilder = $sqlBuilder;
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
}
