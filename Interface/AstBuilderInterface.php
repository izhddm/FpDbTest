<?php

namespace FpDbTest\Interface;

interface AstBuilderInterface
{
    public function buildAst(array $tokens): array;
}