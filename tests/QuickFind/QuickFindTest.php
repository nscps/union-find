<?php

namespace QuickFind;

use InvalidArgumentException;
use Nscps\UnionFind\QuickFind\QuickFind;
use PHPUnit\Framework\TestCase;

class QuickFindTest extends TestCase
{

    /**
     * @test
     */
    public function shouldBeEmptyWhenConstructorHasNoArguments(): void
    {
        $quick_find = new QuickFind();

        $this->assertCount(0, $quick_find);
    }

    /**
     * @test
     */
    public function shouldSetElementsWhenConstructorHasArrayOfString(): void
    {
        $quick_find = new QuickFind(['A', 'B', 'C']);

        $this->assertCount(3, $quick_find);

        $this->assertTrue($quick_find->has('A'));
        $this->assertTrue($quick_find->has('B'));
        $this->assertTrue($quick_find->has('C'));
        $this->assertFalse($quick_find->has('D'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenConstructorArgumentHasDuplicateElement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new QuickFind(['A', 'A', 'B']);
    }

    /**
     * @test
     */
    public function shouldAddSingleElementWhenAddingElement(): void
    {
        $quick_find = new QuickFind();
        $quick_find->add('A');

        $this->assertCount(1, $quick_find);
        $this->assertTrue($quick_find->has('A'));
        $this->assertFalse($quick_find->has('B'));
        $this->assertFalse($quick_find->has('C'));
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenAddingDuplicateElement(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $quick_find = new QuickFind();
        $quick_find->add('A');
        $quick_find->add('A');
    }

}