<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use Checkout\Library\HttpHandler;
use Checkout\Models\Tokens\Card;
use Checkout\Models\Instruments\Instrument;
use PHPUnit\Framework\TestCase;

class InstrumentControllerTest extends TestCase
{
    public function testRetrieve()
    {
        $checkout = new CheckoutApi();

        $cardToken = new Card('4242424242424242', 01, 2025);
        $token = $checkout->tokens()->request($cardToken);

        // Create Instrument
        $ins = new Instrument("token", $token->token);
        $details = $checkout->instruments()->add($ins, HttpHandler::MODE_RETRIEVE);

        $this->assertInstanceOf(HttpHandler::class, $details);
        $this->assertEquals(HttpHandler::METHOD_POST, $details->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_SECRET, $details->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $details->getContentType());
    }
}
