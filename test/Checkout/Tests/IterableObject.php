<?php

namespace Checkout\Tests;

use ArrayIterator;
use IteratorAggregate;

class IterableObject implements IteratorAggregate
{
    public $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    #[\ReturnTypeWillChange]
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
