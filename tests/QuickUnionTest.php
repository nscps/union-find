<?php

namespace Nscps\UnionFind\Tests;

use Nscps\UnionFind\QuickUnion;
use Nscps\UnionFind\UnionFindInterface;

class QuickUnionTest extends BaseUnionFindTest
{

    protected function getUnionFindInstance(array $args = []): UnionFindInterface
    {
        return new QuickUnion(...$args);
    }

}