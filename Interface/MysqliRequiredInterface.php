<?php

namespace FpDbTest\Interface;

use mysqli;

interface MysqliRequiredInterface
{
    public function __construct(mysqli $mysqli);
}