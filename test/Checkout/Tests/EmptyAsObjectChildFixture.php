<?php

namespace Checkout\Tests;

/**
 * Test fixture: inherits #[EmptyAsObject] from its parent without declaring it.
 */
class EmptyAsObjectChildFixture extends EmptyAsObjectParentFixture
{
    /**
     * @var EmptyAsObjectParentFixture
     */
    public $nested;
}
