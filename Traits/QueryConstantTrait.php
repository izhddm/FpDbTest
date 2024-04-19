<?php

namespace FpDbTest\Traits;

trait QueryConstantTrait
{
    private const string PLACEHOLDER_SIGN = '?';
    private const string CONDITIONAL_BLOCK_BEGIN = '{';
    private const string CONDITIONAL_BLOCK_END = '}';
}