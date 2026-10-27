<?php

namespace Checkout\Tests;

use Checkout\Common\EmptyAsObject;

/**
 * Test fixture: a class annotated #[EmptyAsObject].
 */
#[EmptyAsObject]
class EmptyAsObjectParentFixture
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $tags;
}
