<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use Checkout\Library\Exceptions\CheckoutModelException;
use Checkout\Library\HttpHandler;
use Checkout\tests\Helpers\Webhooks;

class WebhookControllerTest extends SandboxTestFixture
{
    /**
     * @before
     */
    public function before()
    {
        $this->init();
    }

    public function testLoad()
    {
        $webhook = $this->checkout->webhooks()->load(Webhooks::generateID(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_GET, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRetrieve()
    {
        $webhook = $this->checkout->webhooks()->retrieve(HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_GET, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRegister()
    {
        $webhook = $this->checkout->webhooks()->register(Webhooks::generateModel(), array('payment_approved'), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_POST, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRegisterNoEvents()
    {
        $webhook = $this->checkout->webhooks()->register(Webhooks::generateModel(), array(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_POST, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testUpdate()
    {
        self::markTestSkipped("review");
        $webhook = $this->checkout->webhooks()->update(Webhooks::generateModel(Webhooks::generateID()), false, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_PUT, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    /**
     */
    public function testUpdateNoID()
    {
        $this->expectException(CheckoutModelException::class);
        $webhook = $this->checkout->webhooks()->update(Webhooks::generateModel(), false, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_PUT, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testUpdatePartially()
    {
        $checkout = new CheckoutApi();
        $webhook = $this->checkout->webhooks()->update(Webhooks::generateModel(Webhooks::generateID()), true, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_PATCH, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }

    public function testRemove()
    {
        $webhook = $this->checkout->webhooks()->remove(Webhooks::generateID(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $webhook);
        $this->assertEquals(HttpHandler::METHOD_DEL, $webhook->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $webhook->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $webhook->getContentType());
    }
}
