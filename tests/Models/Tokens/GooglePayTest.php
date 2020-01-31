<?php

namespace Checkout\Tests\Models\Payments;

use Checkout\Models\Tokens\GooglePay;
use Checkout\Tests\Helpers\Tokens;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class GooglePayTest extends TestCase
{
    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Tokens\GooglePay');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = Tokens::generateGooglePayModel();
        $response = $model->getValues();

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(GooglePay::class, $result);
    }
}
