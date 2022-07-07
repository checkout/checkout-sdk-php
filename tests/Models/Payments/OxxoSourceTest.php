<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\OxxoSource;
use Checkout\Models\Payments\Payer;
use PHPUnit\Framework\TestCase;

class OxxoSourceTest extends TestCase
{
    public function testCreate()
    {
        $model = new OxxoSource(
            "redirect",
            "MX",
            new Payer("Bruce Wayne", "bruce@wayne-enterprises.com", ""),
            "simulate OXXO Demo Payment");
        $this->assertEquals($model::MODEL_NAME, $model->type);
    }
}
