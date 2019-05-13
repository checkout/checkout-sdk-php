<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Refund;
use Checkout\tests\Helpers\Payments;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class RefundTest extends TestCase
{
    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Payments\Refund');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = Payments::generateRefundModel();
        $response = $model->getValues();

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(Refund::class, $result);
    }
}
