<?php

namespace Checkout\tests\Library;

use Checkout\Models\Payments\Payment;
use Checkout\Models\Response;
use Checkout\tests\Helpers\HttpHandlers;
use Checkout\tests\Helpers\Payments;
use Checkout\tests\Helpers\Responses;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ModelTest extends TestCase
{
    public function testGetValue()
    {
        $payment = Payments::generateModel();
        unset($payment->{'3ds'});
        $payment->{'threeDs'} = Payments::generateTheeDS();
        $this->assertTrue(is_array($payment->getValue('3ds')));
    }

    /**
     * @param Response $response Response model
     * @dataProvider providerModels
     */
    public function testIsSuccessful($response, $success)
    {
        $this->assertEquals($success, $response->isSuccessful());
    }

    public function providerModels()
    {
        return array(array(Responses::generateModelError(), false),
                    array(Responses::generateModelSuccess(), true));
    }

    /**
     * @param Response $response Response model
     * @dataProvider providerModels
     */
    public function testGetErrors($response, $success)
    {
        $this->assertEquals(!$success, sizeof($response->getErrors()) > 0);
    }

    /**
     * @param Response $response Response model
     * @dataProvider providerModels
     */
    public function testGetCode($response, $success)
    {
        $this->assertEquals(!$success, $response->getCode() >= 400);
    }

    public function testGetLink()
    {
        $response = Responses::generateModelSuccess();
        $this->assertTrue(is_string($response->getLink('TEST')));
    }

    public function testLoad()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $prop = $class->getProperty('response');
        $prop->setAccessible(true);

        $http = HttpHandlers::generateHandler();
        $prop->setValue($http, json_encode(Payments::generateModel()));

        $model = Payment::load($http);
        $this->assertInstanceOf(Payment::class, $model);
    }

    public function testLoadNull()
    {
        $http = HttpHandlers::generateHandler();
        $model = Payment::load($http);
        $this->assertInstanceOf(Payment::class, $model);
    }

    public function testCreate()
    {
        $class = new ReflectionClass('Checkout\Models\Response');
        $method = $class->getMethod('create');
        $method->setAccessible(true);

        $controller = new Response();

        $method->invoke($controller, array(array()));
    }
}
