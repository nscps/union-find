<?php

namespace Nscps\UnionFind\Tests;

use Nscps\UnionFind\QuickFind;
use Nscps\UnionFind\UnionFindInterface;

class QuickFindTest extends BaseUnionFindTest
{

    protected function getUnionFindInstance(array $args = []): UnionFindInterface
    {
        return new QuickFind(...$args);
    }

}