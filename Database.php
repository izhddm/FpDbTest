<?php

namespace FpDbTest;

use FpDbTest\Factory\ParameterHandlerFactory;
use FpDbTest\Interface\AstBuilderInterface;
use FpDbTest\Interface\CommentRemoverInterface;
use FpDbTest\Interface\DatabaseInterface;
use FpDbTest\Interface\ModifierReplacerInterface;
use FpDbTest\Interface\SqlBuilderInterface;
use FpDbTest\Interface\TokenizerInterface;
use mysqli;

/**
 * Класс Database, класс взаимодействия с базой данных.
 */
class Database implements DatabaseInterface
{
    private const float SKIP = INF;

    private CommentRemoverInterface $commentRemover;
    private TokenizerInterface $tokenizer;
    private AstBuilderInterface $astBuilder;
    private SqlBuilderInterface $sqlBuilder;
    private ModifierReplacerInterface $modifierReplacer;

    public function __construct(protected mysqli $mysqli)
    {
        $this->commentRemover = new CommentRemover();
        $this->tokenizer = new Tokenizer();
        $this->astBuilder = new AstBuilder();
        $this->sqlBuilder = new SqlBuilder();
        $this->modifierReplacer = new ModifierReplacer(new ParameterHandlerFactory());
    }

    public function buildQuery(string $query, array $args = []): string|false
    {
        $sqlParser = new SQLParser($this->commentRemover, $this->tokenizer, $this->astBuilder, $this->sqlBuilder, $this->modifierReplacer);

        // Преобразуем sql запрос в дерево
        $ast = $sqlParser->parse($query);

        // Заменяем модификаторы на значения
        $ast = $sqlParser->replaceModifiers($ast, $args, $this->mysqli);

        // Преобразуем дерево в sql запрос и возвращаем
        return $sqlParser->toSQL($ast);
    }

    public function skip(): float
    {
        return self::SKIP;
    }
}
