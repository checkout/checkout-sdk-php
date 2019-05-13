<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use Checkout\Library\HttpHandler;
use Checkout\Models\Response;
use Checkout\tests\Helpers\HttpHandlers;
use Checkout\tests\Helpers\Payments;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class PaymentControllerTest extends TestCase
{
    public function testRefund()
    {
        $checkout = new CheckoutApi();
        $refundModel = Payments::generateRefundModel();
        $actionID = Payments::generateActionID();
        $refundModel->action_id = $actionID;
        $refund = $checkout->payments()->refund($refundModel, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $refund);
        $this->assertEquals($actionID, $refundModel->getActionId());
        $this->assertEquals(HttpHandler::METHOD_POST, $refund->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $refund->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $refund->getContentType());
    }

    public function testDetails()
    {
        $checkout = new CheckoutApi();
        $payment = Payments::generateModel();
        $payment->id = Payments::generateID();
        $actionID = Payments::generateActionID();
        $payment->action_id = $actionID;
        $details = $checkout->payments()->details($payment->getId(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $details);
        $this->assertEquals($actionID, $payment->getActionId());
        $this->assertEquals(HttpHandler::METHOD_GET, $details->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $details->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $details->getContentType());
    }

    public function testActions()
    {
        $checkout = new CheckoutApi();
        $payment = Payments::generateModel();
        $payment->id = Payments::generateID();
        $actionID = Payments::generateActionID();
        $payment->action_id = $actionID;
        $actions = $checkout->payments()->actions($payment->getId(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $actions);
        $this->assertEquals($actionID, $payment->getActionId());
        $this->assertEquals(HttpHandler::METHOD_GET, $actions->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $actions->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $actions->getContentType());
    }

    public function testVoid()
    {
        $checkout = new CheckoutApi();
        $voidModel = Payments::generateVoidModel();
        $actionID = Payments::generateActionID();
        $voidModel->action_id = $actionID;
        $void = $checkout->payments()->void($voidModel, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $void);
        $this->assertEquals($actionID, $voidModel->getActionId());
        $this->assertEquals(HttpHandler::METHOD_POST, $void->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $void->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $void->getContentType());
    }

    public function testCapture()
    {
        $checkout = new CheckoutApi();
        $captureModel = Payments::generateCaptureModel();
        $actionID = Payments::generateActionID();
        $captureModel->action_id = $actionID;
        $capture = $checkout->payments()->capture($captureModel, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $capture);
        $this->assertEquals($actionID, $captureModel->getActionId());
        $this->assertEquals(HttpHandler::METHOD_POST, $capture->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $capture->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $capture->getContentType());
    }

    public function testRequest()
    {
        $checkout = new CheckoutApi();
        $request = $checkout->payments()->request(Payments::generateModel(), HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $request);
        $this->assertEquals(HttpHandler::METHOD_POST, $request->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $request->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $request->getContentType());
    }

    public function testResponse()
    {
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
}
