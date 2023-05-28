<?php

namespace Nscps\UnionFind\Exception;

use RuntimeException;

class ElementNotFoundException extends RuntimeException
{

    public function __construct(string $element) {
        $message = sprintf('The element "%s" was not found in the union.', $element);

        parent::__construct($message);
    }

}