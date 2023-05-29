<?php

namespace Nscps\UnionFind;

interface UnionFindInterface
{

    /**
     * Add a new element to the union.
     * @param string $p
     * @return $this
     */
    public function add(string $p): UnionFindInterface;

    /**
     * Check if a given element exists.
     * @param string $p
     * @return bool
     */
    public function has(string $p): bool;

    /**
     * Find the root of a given element.
     * @param string $p
     * @return string
     */
    public function getRoot(string $p): string;

    /**
     * Unite two given elements. The root of "q" will be the root of "p".
     * @param string $p
     * @param string $q
     * @return $this
     */
    public function unite(string $p, string $q): UnionFindInterface;

    /**
     * Check if two elements are connected.
     * @param string $p
     * @param string $q
     * @return bool
     */
    public function areConnected(string $p, string $q): bool;

    /**
     * Remove a given element and all its descendents from the union.
     * @param string $p
     * @return $this
     */
    public function remove(string $p): UnionFindInterface;

}