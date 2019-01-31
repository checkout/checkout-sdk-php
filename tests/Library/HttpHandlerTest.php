<?php

namespace Checkout\tests\Library;

use Checkout\Library\Exceptions\CheckoutHttpException;
use Checkout\Library\HttpHandler;
use Checkout\tests\Helpers\HttpHandlers;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class HttpHandlerTest extends TestCase
{
    public function testCreate()
    {
        $result = HttpHandler::create();
        $this->assertInstanceOf(HttpHandler::class, $result);
    }

    public function Execute()
    {
        $http = HttpHandlers::generateHandler();
        $result = $http->execute(HttpHandler::MODE_RETRIEVE);
        $this->assertInstanceOf(HttpHandler::class, $result);
    }

    public function testSerialize()
    {
        $serialized = HttpHandlers::generateHandler()->serialize();

        $this->assertTrue(is_array($serialized));
        $this->assertArrayHasKey('url', $serialized);
        $this->assertArrayHasKey('header', $serialized);
        $this->assertArrayHasKey('method', $serialized);
        $this->assertArrayHasKey('body', $serialized);
    }

    public function testIdempotency()
    {
        $handler = HttpHandlers::generateHandler();
        $uuid =  HttpHandlers::generateUUID();
        $handler->setIdempotencyKey($uuid);
        $headers = $handler->getHeaders();

        $this->assertTrue(in_array('Cko-Idempotency-Key: ' . $uuid, $headers));
    }

    public function testAddHeader()
    {
        $handler = HttpHandlers::generateHandler();
        $header = HttpHandlers::generateHeader();
        $handler->addHeader($header);
        $headers = $handler->getHeaders();

        $this->assertTrue(in_array($header, $headers));
    }


    public function testGetQueryParameters()
    {
        $handler = HttpHandlers::generateHandler();
        $parameters = array('PARAM1' => 'param1',
                           'PARAM1' => 'param1');

        $handler->setQueryParameters($parameters);
        $this->assertEquals($parameters, $handler->getQueryParameters());
    }

    public function testGetQueryParametersQuery()
    {
        $handler = HttpHandlers::generateHandler();
        $parameters = array('PARAM1' => 'param1',
                           'PARAM1' => 'param1');

        $handler->setQueryParameters($parameters);
        $this->assertEquals('?' . http_build_query($parameters), $handler->getQueryParameters(true));
    }

    public function testAddOption()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $property = $class->getProperty('options');
        $property->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $handler->addOption('RUN', 'TEST');

        $options = $property->getValue($handler);

        $this->assertTrue(in_array('TEST', $options));
        $this->assertArrayHasKey('RUN', $options);
    }

    public function testSetContentType()
    {
        $handler = HttpHandlers::generateHandler();
        $type = HttpHandler::MIME_TYPE_HTML;
        $handler->setContentType($type);

        $this->assertEquals('Content-type: ' . $type, $handler->getContentType());
    }

    public function testSetURL()
    {
        $handler = HttpHandlers::generateHandler();
        $old = $handler->getURL();

        $handler->setURL(HttpHandlers::generateURL());
        $this->assertNotEquals($old, $handler->getURL());
    }

    public function testSetAuth()
    {
        $handler = HttpHandlers::generateHandler();
        $old = $handler->getAuth();

        $handler->setAuth(HttpHandler::AUTH_TYPE_PUBLIC);
        $this->assertNotEquals($old, $handler->getAuth());
    }

    public function testOptions()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $method = $class->getMethod('options');
        $method->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $options = $method->invoke($handler);

        $this->assertArrayHasKey(CURLOPT_RETURNTRANSFER, $options);
        $this->assertArrayHasKey(CURLOPT_FAILONERROR, $options);
    }

    public function testGetCode()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $property = $class->getProperty('info');
        $property->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $property->setValue($handler, array('http_code' => 200));

        $this->assertNotNull($handler->getCode());
    }


    public function testBody()
    {
        $http = HttpHandlers::generateHandler();
        $body = '{body}';
        $http->setBody($body);

        $this->assertEquals($body, $http->getBody());
    }



    public function testHandleError()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $method = $class->getMethod('handleError');

        $method->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $curl = curl_init('*');
        curl_exec($curl);
        $result = $method->invokeArgs($handler, array($curl));
        $this->assertInstanceOf(CheckoutHttpException::class, $result);
    }

    public function testHandleErrorCode()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $method = $class->getMethod('handleError');
        $property = $class->getProperty('info');

        $property->setAccessible(true);
        $method->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $property->setValue($handler, array('http_code' => 400));

        $curl = curl_init('*');
        $result = $method->invokeArgs($handler, array($curl));
        $this->assertInstanceOf(CheckoutHttpException::class, $result);
    }

    public function testHandleErrorSuccess()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $method = $class->getMethod('handleError');
        $property = $class->getProperty('info');

        $property->setAccessible(true);
        $method->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $property->setValue($handler, array('http_code' => 200));

        $curl = curl_init('*');
        $result = $method->invokeArgs($handler, array($curl));
        $this->assertNull($result);
    }

    public function testSetUpCurl()
    {
        $class = new ReflectionClass('Checkout\Library\HttpHandler');
        $method = $class->getMethod('setUpCurl');

        $method->setAccessible(true);

        $handler = HttpHandlers::generateHandler();
        $handler->setBody('{body}');

        $curl = curl_init();
        $method->invokeArgs($handler, array($curl));
    }

    /**
     * @expectedException Checkout\Library\Exceptions\CheckoutHttpException
     */
    public function testExecuteError()
    {
        HttpHandler::$throw = true;
        $handler = HttpHandlers::generateHandler();
        $handler->execute();
    }


    public function testExecute()
    {
        HttpHandler::$throw = false;
        $handler = HttpHandlers::generateHandler();
        $result = $handler->execute();

        $this->assertInstanceOf(HttpHandler::class, $result);
    }
}
