<?php

namespace Nscps\UnionFind\Exception;

use RuntimeException;

class DuplicateElementException extends RuntimeException
{

    public function __construct(string $element) {
        $message = sprintf('The element "%s" was already added to the union.', $element);

        parent::__construct($message);
    }

}