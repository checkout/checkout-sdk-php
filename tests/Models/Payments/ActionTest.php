<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Payments\Action;
use Checkout\Models\Response;
use Checkout\tests\Helpers\Payments;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ActionTest extends TestCase
{
    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Payments\Action');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = Payments::generateActionsModel();
        $response = $model->getValues();

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(Action::class, $result);
    }

    public function testCreateMultiple()
    {
        $class = new ReflectionClass('Checkout\Models\Payments\Action');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = Payments::generateActionsModel();
        $response = array($model->getValues());

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(Response::class, $result);
    }
}
