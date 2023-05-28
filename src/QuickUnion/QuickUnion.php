<?php

namespace Nscps\UnionFind\QuickUnion;

use Countable;
use InvalidArgumentException;
use SplQueue;

class QuickUnion implements Countable, QuickUnionInterface
{

    /**
     * @var string[]
     */
    private $union_find = [];

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
    public function add(string $p): QuickUnionInterface
    {
        $this->assertElementNotExists($p);

        $this->union_find[$p] = $p;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function root(string $p): string
    {
        $this->assertElementExists($p);

        while ($p !== $this->union_find[$p]) {
            $p = $this->union_find[$p];
        }

        return $p;
    }

    /**
     * @inheritDoc
     */
    public function has(string $p): bool
    {
        return array_key_exists($p, $this->union_find);
    }

    /**
     * @inheritDoc
     */
    public function unite(string $p, string $q): QuickUnionInterface
    {
        $this->assertElementExists($p);
        $this->assertElementExists($q);

        $i = $this->root($p);
        $j = $this->root($q);

        $this->union_find[$i] = $j;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function areConnected(string $p, string $q): bool
    {
        $this->assertElementExists($p);
        $this->assertElementExists($q);

        return $this->root($p) === $this->root($q);
    }

    /**
     * @inheritDoc
     */
    public function remove(string $p): QuickUnionInterface
    {
        $this->assertElementExists($p);

        foreach ($this->descendents($p) as $descendent) {
            unset($this->union_find[$descendent]);
        }

        unset($this->union_find[$p]);

        return $this;
    }

    /**
     * Returns a list of all descendents of a given element.
     * @param string $p
     * @return string[]
     */
    private function descendents(string $p): array
    {
        $descendents = [];

        $queue = new SplQueue();
        $queue->enqueue($p);

        while (!$queue->isEmpty()) {
            $q = $queue->dequeue();

            // Add all children of "$q" to the queue
            foreach ($this->union_find as $r => $s) {
                if ($r !== $q && $s === $q) {
                    $descendents[] = $r;
                    $queue->enqueue($r);
                }
            }
        }

        return array_unique($descendents);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->union_find);
    }

    private function assertElementExists(string $p): void
    {
        if (!$this->has($p)) {
            throw new InvalidArgumentException(sprintf('Element "%s" was not added.', $p));
        }
    }

    private function assertElementNotExists(string $p): void
    {
        if ($this->has($p)) {
            throw new InvalidArgumentException(sprintf('Element "%s" was already added.', $p));
        }
    }

}