<?php

namespace Nscps\UnionFind\Tests\QuickUnion;

use InvalidArgumentException;
use Nscps\UnionFind\QuickUnion\QuickUnion;
use PHPUnit\Framework\TestCase;

class QuickUnionTest extends TestCase
{

    /**
     * @test
     */
    public function shouldBeEmptyWhenConstructorHasNoArguments(): void
    {
        $union = new QuickUnion();

        $this->assertCount(0, $union);
    }

    /**
     * @test
     */
    public function shouldSetElementsWhenConstructorHasArrayOfString(): void
    {
        $union = new QuickUnion(['A', 'B', 'C']);

        $this->assertCount(3, $union);

        $this->assertTrue($union->has('A'));
        $this->assertTrue($union->has('B'));
        $this->assertTrue($union->has('C'));
        $this->assertFalse($union->has('D'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenConstructorArgumentHasDuplicateElement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new QuickUnion(['A', 'A', 'B']);
    }

    /**
     * @test
     */
    public function shouldAddSingleElementWhenAddingElement(): void
    {
        $union = new QuickUnion();

        $union->add('A');

        $this->assertCount(1, $union);
        $this->assertTrue($union->has('A'));
        $this->assertFalse($union->has('B'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenAddingDuplicateElement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $union = new QuickUnion();

        $union->add('A');
        $union->add('A');
    }

    /**
     * @test
     */
    public function shouldReturnSameIdWhenElementIsNotUnited(): void
    {
        $union = new QuickUnion();

        $union->add('A');
        $union->add('B');

        $this->assertSame('A', $union->root('A'));
        $this->assertSame('B', $union->root('B'));

        $this->assertTrue($union->find('A', 'A'));
        $this->assertTrue($union->find('B', 'B'));
        $this->assertFalse($union->find('A', 'B'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenGettingRootOfMissingElement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $union = new QuickUnion();

        $union->add('A');
        $union->root('B');
    }

    /**
     * @test
     */
    public function shouldSetQAsParentOfP(): void
    {
        $union = new QuickUnion();

        $union->add('A');
        $union->add('B');
        $union->add('C');

        $union->unite('A', 'B');

        $this->assertSame('B', $union->root('A'));
        $this->assertSame('B', $union->root('B'));
        $this->assertSame('C', $union->root('C'));
    }

    /**
     * @test
     */
    public function shouldSetRootOfQAsRootOfP(): void
    {
        $union = new QuickUnion();

        $union->add('A');
        $union->add('B');
        $union->add('C');
        $union->add('D');
        $union->add('E');

        // A->B, C->D
        $union->unite('A', 'B');
        $union->unite('C', 'D');

        $this->assertSame('B', $union->root('A'));
        $this->assertSame('B', $union->root('B'));
        $this->assertSame('D', $union->root('C'));
        $this->assertSame('D', $union->root('D'));
        $this->assertSame('E', $union->root('E'));

        // A->B->D, C->D
        $union->unite('A', 'C');

        $this->assertSame('D', $union->root('A'));
        $this->assertSame('D', $union->root('B'));
        $this->assertSame('D', $union->root('C'));
        $this->assertSame('D', $union->root('D'));
        $this->assertSame('E', $union->root('E'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenUnitingMissingElements(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $union = new QuickUnion();

        $union->unite('A', 'B');
    }

    /**
     * @test
     */
    public function shouldRemoveSingleElementWhenRemovingLeaf(): void
    {
        $union = new QuickUnion();

        $union->add('A');
        $union->add('B');

        $union->remove('B');

        $this->assertCount(1, $union);
        $this->assertTrue($union->has('A'));
        $this->assertFalse($union->has('B'));
    }

    /**
     * @test
     */
    public function shouldRemoveElementAndItsChildrenWhenRemovingElement(): void
    {
        $union = new QuickUnion();

        $union->add('A');
        $union->add('B');
        $union->add('C');
        $union->add('D');

        $union->unite('B', 'A');
        $union->unite('C', 'A');
        $union->remove('A');

        $this->assertCount(1, $union);
        $this->assertFalse($union->has('A'));
        $this->assertFalse($union->has('B'));
        $this->assertFalse($union->has('C'));
        $this->assertTrue($union->has('D'));
    }

    /**
     * @test
     */
    public function shouldRemoveElementAndItsDescendentsWhenRemovingElement(): void
    {
        $union = new QuickUnion();

        $union->add('A');
        $union->add('B');
        $union->add('C');
        $union->add('D');
        $union->add('E');

        // A->B->D, C->D, E->E
        $union->unite('A', 'B');
        $union->unite('C', 'D');
        $union->unite('A', 'C');

        $union->remove('D');

        $this->assertCount(1, $union);
        $this->assertFalse($union->has('A'));
        $this->assertFalse($union->has('B'));
        $this->assertFalse($union->has('C'));
        $this->assertFalse($union->has('D'));
        $this->assertTrue($union->has('E'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenRemovingMissingElement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $union = new QuickUnion();

        $union->remove('A');
    }

}