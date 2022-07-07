<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Destination;
use Checkout\Models\Payments\TokenDestination;
use PHPUnit\Framework\TestCase;

class DestinationTest extends TestCase
{
    public function testCreate()
    {
        $model = new TokenDestination('{id}', 1000, '{last}');
        $this->assertInstanceOf(Destination::class, $model);
    }
}
