<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use Checkout\Library\HttpHandler;
use Checkout\tests\Helpers\Webhooks;
use PHPUnit\Framework\TestCase;

class WebhookControllerTest extends TestCase
{
    public function testLoad()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->load(Webhooks::generateID(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_GET, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRetrieve()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->retrieve(HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_GET, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRegister()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->register(Webhooks::generateModel(), array('payment_approved'), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_POST, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    /**
     * @expectedException Checkout\Library\Exceptions\CheckoutModelException
     */
    public function testRegisterNoEvents()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->register(Webhooks::generateModel(), array(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_POST, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testUpdate()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->update(Webhooks::generateModel(Webhooks::generateID()), false, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_PUT, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    /**
     * @expectedException Checkout\Library\Exceptions\CheckoutModelException
     */
    public function testUpdateNoID()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->update(Webhooks::generateModel(), false, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_PUT, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testUpdatePartially()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->update(Webhooks::generateModel(Webhooks::generateID()), true, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_PATCH, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRemove()
    {
        $checkout = new CheckoutApi();
        $webhook = $checkout->webhooks()->remove(Webhooks::generateID(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_DEL, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }
}
