<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Risk;
use PHPUnit\Framework\TestCase;

class RiskTest extends TestCase
{
    public function testCreate()
    {
        $model = new Risk(true);
        $this->assertInstanceOf(Risk::class, $model);
    }
}
