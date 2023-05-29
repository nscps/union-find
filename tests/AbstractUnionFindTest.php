<?php

namespace Nscps\UnionFind\Tests;

use Nscps\UnionFind\AbstractUnionFind;
use Nscps\UnionFind\Exception\DuplicateElementException;
use Nscps\UnionFind\Exception\ElementNotFoundException;
use PHPUnit\Framework\TestCase;

class AbstractUnionFindTest extends TestCase
{

    public function testEmptyConstructor(): void
    {
        $union_find = $this->getUnionFindInstance();

        $this->assertCount(0, $union_find);
    }

    public function testConstructorWithElements(): void
    {
        $union_find = $this->getUnionFindInstance(['A', 'B', 'C']);

        $this->assertCount(3, $union_find);

        $this->assertTrue($union_find->has('A'));
        $this->assertTrue($union_find->has('B'));
        $this->assertTrue($union_find->has('C'));
        $this->assertFalse($union_find->has('D'));
    }

    public function testConstructorWithDuplicateElement(): void
    {
        $this->expectException(DuplicateElementException::class);

        $this->getUnionFindInstance(['A', 'A', 'C']);
    }

    public function testAddAndHas(): void
    {
        $union_find = $this->getUnionFindInstance();

        $this->assertFalse($union_find->has('A'));
        $this->assertFalse($union_find->has('B'));

        $union_find->add('A');

        $this->assertTrue($union_find->has('A'));
        $this->assertFalse($union_find->has('B'));
    }

    public function testAddWithDuplicateElement(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');

        $this->expectException(DuplicateElementException::class);
        $union_find->add('A');
    }

    public function testAreConnectedWithMissingElement(): void
    {
        $union_find = $this->getUnionFindInstance();

        $union_find->add('A');
        $union_find->add('B');

        $this->expectException(ElementNotFoundException::class);
        $union_find->areConnected('A', 'C');
    }

    public function testCount(): void
    {
        $union_find = $this->getUnionFindInstance();
        $this->assertCount(0, $union_find);

        $union_find->add('A');
        $this->assertCount(1, $union_find);

        $union_find->add('B');
        $this->assertCount(2, $union_find);
    }

    protected function getUnionFindInstance(array $elements = []): AbstractUnionFind
    {
        return $this->getMockForAbstractClass(AbstractUnionFind::class, [$elements]);
    }

}