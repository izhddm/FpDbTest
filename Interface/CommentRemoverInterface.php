<?php

namespace FpDbTest\Interface;

interface CommentRemoverInterface
{
    public function removeComments(string $query): string;
}