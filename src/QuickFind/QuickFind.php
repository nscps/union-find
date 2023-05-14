<?php

namespace Nscps\UnionFind\QuickFind;

use Countable;
use InvalidArgumentException;

class QuickFind implements Countable
{

    /**
     * @var string[]
     */
    private $quick_find = [];

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
     * Add an element to the Quick Find.
     * @param string $p
     * @return $this
     */
    public function add(string $p): self
    {
        if ($this->has($p)) {
            throw new InvalidArgumentException(sprintf(
                'Element "%s" was already added to the Quick Find.',
                $p
            ));
        }

        $this->quick_find[$p] = $p;

        return $this;
    }

    /**
     * Check if a given element was added to the Quick Find.
     * @param string $p
     * @return bool
     */
    public function has(string $p): bool
    {
        return array_key_exists($p, $this->quick_find);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return count($this->quick_find);
    }

}