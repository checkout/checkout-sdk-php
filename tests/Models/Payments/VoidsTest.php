<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Voids;
use Checkout\tests\Helpers\Payments;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class VoidsTest extends TestCase
{
    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Payments\Voids');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = Payments::generateVoidModel();
        $response = $model->getValues();

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(Voids::class, $result);
    }
}
