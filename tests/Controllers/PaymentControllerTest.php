<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use Checkout\Library\Exceptions\CheckoutHttpException;
use Checkout\Library\HttpHandler;
use Checkout\Models\Payments\OxxoSource;
use Checkout\Models\Payments\Payer;
use Checkout\Models\Payments\Payment;
use Checkout\Models\Response;
use Checkout\tests\Helpers\HttpHandlers;
use Checkout\tests\Helpers\Payments;
use Exception;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PaymentControllerTest extends SandboxTestFixture
{
    /**
     * @before
     */
    public function before()
    {
        $this->init();
    }

    public function testRefund()
    {
        $refundModel = Payments::generateRefundModel();
        $actionID = Payments::generateActionID();
        $refundModel->action_id = $actionID;
        $refund = $this->checkout->payments()->refund($refundModel, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $refund);
        $this->assertEquals($actionID, $refundModel->getActionId());
        $this->assertEquals(HttpHandler::METHOD_POST, $refund->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $refund->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $refund->getContentType());
    }

    public function testDetails()
    {
        $payment = Payments::generateModel();
        $payment->id = Payments::generateID();
        $actionID = Payments::generateActionID();
        $payment->action_id = $actionID;
        $details = $this->checkout->payments()->details($payment->getId(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $details);
        $this->assertEquals($actionID, $payment->getActionId());
        $this->assertEquals(HttpHandler::METHOD_GET, $details->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $details->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $details->getContentType());
    }

    public function testActions()
    {
        $payment = Payments::generateModel();
        $payment->id = Payments::generateID();
        $actionID = Payments::generateActionID();
        $payment->action_id = $actionID;
        $actions = $this->checkout->payments()->actions($payment->getId(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $actions);
        $this->assertEquals($actionID, $payment->getActionId());
        $this->assertEquals(HttpHandler::METHOD_GET, $actions->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $actions->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $actions->getContentType());
    }

    public function testVoid()
    {
        $voidModel = Payments::generateVoidModel();
        $actionID = Payments::generateActionID();
        $voidModel->action_id = $actionID;
        $void = $this->checkout->payments()->void($voidModel, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $void);
        $this->assertEquals($actionID, $voidModel->getActionId());
        $this->assertEquals(HttpHandler::METHOD_POST, $void->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $void->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $void->getContentType());
    }

    public function testCapture()
    {
        $captureModel = Payments::generateCaptureModel();
        $actionID = Payments::generateActionID();
        $captureModel->action_id = $actionID;
        $capture = $this->checkout->payments()->capture($captureModel, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $capture);
        $this->assertEquals($actionID, $captureModel->getActionId());
        $this->assertEquals(HttpHandler::METHOD_POST, $capture->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $capture->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $capture->getContentType());
    }

    public function testRequest()
    {
        $request = $this->checkout->payments()->request(Payments::generateModel(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $request);
        $this->assertEquals(HttpHandler::METHOD_POST, $request->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $request->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $request->getContentType());
    }

    public function testResponse()
    {
        $this->markTestSkipped("unstable");
        $class = new ReflectionClass('Checkout\Controllers\PaymentController');
        $method = $class->getMethod('response');
        $method->setAccessible(true);

        $checkout = new CheckoutApi();
        $controller = call_user_func(array($checkout, 'payments'));

        $handler = HttpHandlers::generateHandler();
        $mock = $this->getMock(HttpHandler::class);
        $mock->expects($this->any())
                ->method('execute')
                ->willReturn($handler);

        $id = Payments::generateID();

        $result = $method->invokeArgs($controller, array($mock, 'Checkout\Models\Response', HttpHandler::MODE_EXECUTE, $id));
        $this->assertInstanceOf(Response::class, $result);
        $this->assertEquals($id, $result->getId());
    }

    public function testOxxoRequest()
    {
        $source = new OxxoSource(
            "redirect",
            "MX",
            new Payer("Bruce Wayne", "bruce@wayne-enterprises.com", ""),
            "simulate OXXO Demo Payment");

        $payment = new Payment($source, "MXN");
        $payment->amount = 100;

        try {
            $request = $this->checkout->payments()->request($payment, HttpHandler::MODE_EXECUTE);
        } catch (Exception $ex) {
            $this->assertEquals("422", $ex->getCode());
            self::assertEquals("business_not_onboarded", $ex->getErrors()[0]);
        }
    }
}
