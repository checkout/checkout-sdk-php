<?php

namespace Checkout\tests\Library;

use Checkout\CheckoutApi;
use Checkout\Library\HttpHandler;
use Checkout\Models\Response;
use Checkout\tests\Helpers\HttpHandlers;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class ControllerTest extends TestCase
{

    /**
     * @param string $name Controller name
     * @dataProvider providerRequestApi
     */
    public function testRequestApi($name)
    {
        $class = new ReflectionClass('Checkout\Library\Controller');
        $method = $class->getMethod('requestAPI');
        $method->setAccessible(true);

        $checkout = new CheckoutApi();
        $controller = call_user_func(array($checkout, $name));

        foreach (HttpHandler::AUTH_TYPES as $key => $value) {
            $result = $method->invokeArgs($controller, array($name, $key));

            $this->assertInstanceOf(HttpHandler::class, $result);
            $this->assertEquals($result->getAuth(), $key);
        }
    }

    public function providerRequestApi()
    {
        return array(
            array('payments'),
            array('tokens'),
            array('sources'),
            array('files'),
            array('webhooks'),
            array('events')
        );
    }

    /**
     * @param string $name Controller name
     * @dataProvider providerRequestApi
     */
    public function testResponse($name)
    {
        $class = new ReflectionClass('Checkout\Library\Controller');
        $method = $class->getMethod('response');
        $method->setAccessible(true);

        $checkout = new CheckoutApi();
        $controller = call_user_func(array($checkout, $name));

        $handler = HttpHandlers::generateHandler();
        $mock = $this->getMock(HttpHandler::class);
        $mock->expects($this->any())
                ->method('execute')
                ->willReturn($handler);

        $result = $method->invokeArgs($controller, array($mock, 'Checkout\Models\Response'));
        $this->assertInstanceOf(Response::class, $result);
    }

    /**
     * @param string $name Controller name
     * @dataProvider providerRequestApi
     */
    public function testResponseHttp($name)
    {
        $class = new ReflectionClass('Checkout\Library\Controller');
        $method = $class->getMethod('response');
        $method->setAccessible(true);

        $checkout = new CheckoutApi();
        $controller = call_user_func(array($checkout, $name));

        $handler = HttpHandlers::generateHandler();
        $result = $method->invokeArgs($controller, array($handler, 'Checkout\Models\Response', HttpHandler::MODE_RETRIEVE));

        $this->assertEquals($handler, $result);
    }
}
