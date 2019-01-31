<?php

namespace Checkout\tests\Models\Payments;

use Checkout\Models\Events\EventType;
use Checkout\Models\Response;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class EventTypeTest extends TestCase
{
    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Events\EventType');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = new EventType();
        $model->event_types = array('TEST_EVENT');
        $response = $model->getValues();

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(EventType::class, $result);
    }

    public function testCreateMultiple()
    {
        $class = new ReflectionClass('Checkout\Models\Events\EventType');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $model = new EventType();
        $model->event_types = array('TEST_EVENT');
        $response = array($model->getValues());

        $result = $method->invokeArgs($model, array($response));
        $this->assertInstanceOf(Response::class, $result);
    }

    public function testGetTypes()
    {
        $model = new EventType();
        $model->event_types = array('TEST_EVENT');

        $this->assertTrue(is_array($model->getTypes()));
    }
}
