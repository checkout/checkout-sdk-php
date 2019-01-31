<?php

namespace Checkout\tests\Library;

use Checkout\Library\Exceptions\CheckoutException;
use PHPUnit\Framework\TestCase;

class CheckoutExceptionTest extends TestCase
{
    public function testConstructor()
    {
        $exception = new CheckoutException('RUN TEST', 1000);
        $this->assertTrue(strrpos($exception->getMessage(), ' (Code: 1000)') >= 0);
    }
}
