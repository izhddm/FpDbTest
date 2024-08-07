<?php

namespace FpDbTest;

use FpDbTest\Interface\CommentRemoverInterface;

class CommentRemover implements CommentRemoverInterface
{

    public function removeComments(string $query): string
    {
        return preg_replace('/--.*$/m', '', preg_replace('/\/\*.*?\*\//s', '', $query));
    }
}