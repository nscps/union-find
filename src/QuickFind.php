<?php

namespace Nscps\UnionFind;

class QuickFind extends AbstractUnionFind
{

    /**
     * @inheritDoc
     */
    public function getRoot(string $p): string
    {
        $this->assertElementExists($p);

        return $this->union_find[$p];
    }

    /**
     * @inheritDoc
     */
    public function unite(string $p, string $q): UnionFindInterface
    {
        $this->assertElementExists($p);
        $this->assertElementExists($q);

        $p_id = $this->union_find[$p];
        $q_id = $this->union_find[$q];

        foreach ($this->union_find as $i => $value) {
            if ($value === $p_id) {
                $this->union_find[$i] = $q_id;
            }
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function remove(string $p): UnionFindInterface
    {
        $this->assertElementExists($p);

        $p_value = $this->union_find[$p];

        foreach ($this->union_find as $key => $value) {
            if ($value === $p_value) {
                unset($this->union_find[$key]);
            }
        }

        return $this;
    }

}