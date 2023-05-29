<?php

namespace Nscps\UnionFind;

use SplQueue;

class QuickUnion extends AbstractUnionFind
{

    /**
     * @inheritDoc
     */
    public function getRoot(string $p): string
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
    public function unite(string $p, string $q): UnionFindInterface
    {
        $this->assertElementExists($p);
        $this->assertElementExists($q);

        $i = $this->getRoot($p);
        $j = $this->getRoot($q);

        $this->union_find[$i] = $j;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $p): UnionFindInterface
    {
        $this->assertElementExists($p);

        foreach ($this->getDescendents($p) as $descendent) {
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
    protected function getDescendents(string $p): array
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

}