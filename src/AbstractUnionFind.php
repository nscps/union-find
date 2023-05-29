<?php

namespace Nscps\UnionFind;

use Countable;
use Nscps\UnionFind\Exception\DuplicateElementException;
use Nscps\UnionFind\Exception\ElementNotFoundException;

abstract class AbstractUnionFind implements Countable, UnionFindInterface
{

    /**
     * @var string[]
     */
    protected $union_find = [];

    /**
     * @param string[] $elements
     */
    public function __construct(array $elements = [])
    {
        foreach ($elements as $element) {
            $this->add($element);
        }
    }

    /**
     * @inheritDoc
     */
    public function add(string $p): UnionFindInterface
    {
        $this->assertElementNotExists($p);

        $this->union_find[$p] = $p;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function areConnected(string $p, string $q): bool
    {
        $this->assertElementExists($p);
        $this->assertElementExists($q);

        return $this->getRoot($p) === $this->getRoot($q);
    }

    /**
     * Check if a given element was added to the Quick Find.
     * @param string $p
     * @return bool
     */
    public function has(string $p): bool
    {
        return array_key_exists($p, $this->union_find);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->union_find);
    }

    protected function assertElementExists(string $p): void
    {
        if (!$this->has($p)) {
            throw new ElementNotFoundException($p);
        }
    }

    protected function assertElementNotExists(string $p): void
    {
        if ($this->has($p)) {
            throw new DuplicateElementException($p);
        }
    }

}