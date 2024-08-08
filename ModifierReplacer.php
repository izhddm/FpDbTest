<?php

namespace FpDbTest;

use FpDbTest\Factory\ParameterHandlerFactory;
use FpDbTest\Interface\ModifierReplacerInterface;
use mysqli;

class ModifierReplacer implements ModifierReplacerInterface
{
    private ParameterHandlerFactory $factory;

    public function __construct(ParameterHandlerFactory $factory)
    {
        $this->factory = $factory;
    }

    public function replace(array $ast,array $modifiers, mysqli $mysqli): array
    {

        return $ast;
    }
}