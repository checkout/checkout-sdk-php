<?php

namespace Checkout\tests;

use Checkout\CheckoutApi;
use Checkout\Library\HttpHandler;
use Checkout\Library\LogHandler;
use Checkout\Library\Model;
use Checkout\tests\Helpers\CheckoutConfigurations;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CheckoutApiTest extends TestCase
{
    public function testConfiguration()
    {
        $checkout = new CheckoutApi();

        $configuration = CheckoutConfigurations::generateModel();
        $this->assertEquals($configuration, $checkout->configuration($configuration));
    }

    public function testLoadChannel()
    {
        $secret = '{override_secret}';
        $sandbox = true;
        $public = '{override_public}';

        $checkout = new CheckoutApi($secret, $sandbox, $public);

        $configuration = $checkout->configuration();

        $this->assertEquals($secret, $configuration->getSecretKey());
        $this->assertEquals($sandbox, $configuration->getSandbox());
        $this->assertEquals($public, $configuration->getPublicKey());
    }

    // public function testLoadLogs()
    // {
    //     $class = new ReflectionClass('Checkout\CheckoutApi');
    //     $method = $class->getMethod('loadLogs');
    //     $method->setAccessible(true);

    //     $checkout = $class->newInstanceWithoutConstructor();
    //     $method->invokeArgs($checkout, array(CheckoutApi::CONFIG_SECTION_LOGS => array(CheckoutApi::CONFIG_LOGGING => false)));

    //     $this->assertNull(LogHandler::$error);
    //     $this->assertNull(LogHandler::$request);
    //     $this->assertNull(LogHandler::$response);
    // }

    // public function testLoadAliases()
    // {
    //     $class = new ReflectionClass('Checkout\CheckoutApi');
    //     $method = $class->getMethod('loadAliases');
    //     $method->setAccessible(true);

    //     $checkout = $class->newInstanceWithoutConstructor();
    //     $method->invokeArgs($checkout, array(array('threeDs' => '3ds')));

    //     $this->assertArrayHasKey('threeDs', Model::$aliases);
    // }

    // public function testLoadCurl()
    // {
    //     $class = new ReflectionClass('Checkout\CheckoutApi');
    //     $method = $class->getMethod('loadCurl');
    //     $method->setAccessible(true);

    //     $checkout = $class->newInstanceWithoutConstructor();
    //     $method->invokeArgs($checkout, array(array('CURLOPT_SSL_VERIFYPEER' => 1)));

    //     $this->assertArrayHasKey('CURLOPT_SSL_VERIFYPEER', HttpHandler::$config);
    // }

    // public function testLoadHttp()
    // {
    //     $class = new ReflectionClass('Checkout\CheckoutApi');
    //     $method = $class->getMethod('loadHttp');
    //     $method->setAccessible(true);

    //     $checkout = $class->newInstanceWithoutConstructor();
    //     $method->invokeArgs($checkout, array(array('exceptions' => false)));

    //     $this->assertFalse(HttpHandler::$throw);
    // }
}
