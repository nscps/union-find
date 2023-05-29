<?php

namespace Nscps\UnionFind\Tests;

use Nscps\UnionFind\Exception\ElementNotFoundException;
use Nscps\UnionFind\UnionFindInterface;
use PHPUnit\Framework\TestCase;

abstract class BaseUnionFindTest extends TestCase
{

    abstract protected function getUnionFindInstance(array $args = []): UnionFindInterface;

    /**
     * @test
     */
    public function shouldReturnTrueAfterUnitingElements(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');
        $union_find->add('C');
        $union_find->add('D');

        // A,B->B; C->C, D->D
        $union_find->unite('A', 'B');

        $this->assertTrue($union_find->areConnected('A', 'B'));
        $this->assertFalse($union_find->areConnected('A', 'C'));
        $this->assertFalse($union_find->areConnected('A', 'D'));
        $this->assertFalse($union_find->areConnected('B', 'C'));
        $this->assertFalse($union_find->areConnected('B', 'D'));
        $this->assertFalse($union_find->areConnected('C', 'D'));

        // A,B->B; C,D->D
        $union_find->unite('C', 'D');

        $this->assertTrue($union_find->areConnected('A', 'B'));
        $this->assertFalse($union_find->areConnected('A', 'C'));
        $this->assertFalse($union_find->areConnected('A', 'D'));
        $this->assertFalse($union_find->areConnected('B', 'C'));
        $this->assertFalse($union_find->areConnected('B', 'D'));
        $this->assertTrue($union_find->areConnected('C', 'D'));

        // A,B,C,D->D
        $union_find->unite('A', 'C');

        $this->assertTrue($union_find->areConnected('A', 'B'));
        $this->assertTrue($union_find->areConnected('A', 'C'));
        $this->assertTrue($union_find->areConnected('A', 'D'));
        $this->assertTrue($union_find->areConnected('B', 'C'));
        $this->assertTrue($union_find->areConnected('B', 'D'));
        $this->assertTrue($union_find->areConnected('C', 'D'));
    }

    /**
     * @test
     */
    public function shouldReturnTrueWhenCheckingConnectionWithItself(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');
        $union_find->add('C');

        $this->assertTrue($union_find->areConnected('A', 'A'));
        $this->assertTrue($union_find->areConnected('B', 'B'));
        $this->assertTrue($union_find->areConnected('C', 'C'));

        $union_find->unite('A', 'B');

        $this->assertTrue($union_find->areConnected('A', 'A'));
        $this->assertTrue($union_find->areConnected('B', 'B'));
        $this->assertTrue($union_find->areConnected('C', 'C'));
    }

    /**
     * @test
     */
    public function shouldReturnSameResultWhenSwappingParametersOfAreConnected(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');
        $union_find->add('C');

        // A,B->B; C->C
        $union_find->unite('A', 'B');

        $this->assertTrue($union_find->areConnected('A', 'B'));
        $this->assertTrue($union_find->areConnected('B', 'A'));
        $this->assertFalse($union_find->areConnected('A', 'C'));
        $this->assertFalse($union_find->areConnected('C', 'A'));
        $this->assertFalse($union_find->areConnected('B', 'C'));
        $this->assertFalse($union_find->areConnected('C', 'B'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGettingRootOfMissingElement(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');

        $this->expectException(ElementNotFoundException::class);
        $union_find->getRoot('B');
    }

    /**
     * @test
     */
    public function shouldChangeRootWhenUnitingElements(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');
        $union_find->add('C');
        $union_find->add('D');

        // A->A; B->B; C->C; D->D
        $this->assertSame('A', $union_find->getRoot('A'));
        $this->assertSame('B', $union_find->getRoot('B'));
        $this->assertSame('C', $union_find->getRoot('C'));
        $this->assertSame('D', $union_find->getRoot('D'));

        // A,B->B; C->C; D->D
        $union_find->unite('A', 'B');
        $this->assertSame('B', $union_find->getRoot('A'));
        $this->assertSame('B', $union_find->getRoot('B'));
        $this->assertSame('C', $union_find->getRoot('C'));
        $this->assertSame('D', $union_find->getRoot('D'));

        // A,B->B; C,D->D
        $union_find->unite('C', 'D');
        $this->assertSame('B', $union_find->getRoot('A'));
        $this->assertSame('B', $union_find->getRoot('B'));
        $this->assertSame('D', $union_find->getRoot('C'));
        $this->assertSame('D', $union_find->getRoot('D'));

        // A,B,C,D->D
        $union_find->unite('A', 'C');
        $this->assertSame('D', $union_find->getRoot('A'));
        $this->assertSame('D', $union_find->getRoot('B'));
        $this->assertSame('D', $union_find->getRoot('C'));
        $this->assertSame('D', $union_find->getRoot('D'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenUnitingMissingElements(): void
    {
        $union_find = $this->getUnionFindInstance();

        $this->expectException(ElementNotFoundException::class);
        $union_find->unite('A', 'B');
    }

    /**
     * @test
     */
    public function shouldRemoveSingleElementWhenElementIsLeaf(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');

        $union_find->remove('B');

        $this->assertTrue($union_find->has('A'));
        $this->assertFalse($union_find->has('B'));
    }

    /**
     * @test
     */
    public function shouldRemoveSubTreeWhenElementHasDescendents(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');
        $union_find->add('C');
        $union_find->add('D');
        $union_find->add('E');

        // A->B->D, C->D, E->E
        $union_find->unite('A', 'B');
        $union_find->unite('C', 'D');
        $union_find->unite('A', 'C');

        $union_find->remove('D');

        $this->assertFalse($union_find->has('A'));
        $this->assertFalse($union_find->has('B'));
        $this->assertFalse($union_find->has('C'));
        $this->assertFalse($union_find->has('D'));
        $this->assertTrue($union_find->has('E'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenRemovingMissingElement(): void
    {
        $union_find = $this->getUnionFindInstance();

        $this->expectException(ElementNotFoundException::class);
        $union_find->remove('A');
    }

}