<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use PHPUnit\Framework\TestCase;

abstract class SandboxTestFixture extends TestCase
{

    /**
     * @var CheckoutApi
     */
    protected $checkout;

    protected function init()
    {
        $this->checkout = new CheckoutApi(getenv("CHECKOUT_SECRET_KEY"), true, getenv("CHECKOUT_PUBLIC_KEY"));
    }
}
