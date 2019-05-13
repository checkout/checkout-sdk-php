<?php

namespace Checkout\tests\Controllers;

use Checkout\CheckoutApi;
use Checkout\Library\HttpHandler;
use Checkout\tests\Helpers\Tokens;
use PHPUnit\Framework\TestCase;

class TokenControllerTest extends TestCase
{
    public function testRequest()
    {
        $checkout = new CheckoutApi();
        $model = Tokens::generateCardModel();
        $id = Tokens::generateID();
        $model->token = $id;
        $token = $checkout->tokens()->request($model, HttpHandler::MODE_RETRIEVE);

        $this->assertEquals($id, $model->getTokenId());
        $this->assertInstanceOf(HttpHandler::class, $token);
        $this->assertEquals(HttpHandler::METHOD_POST, $token->getMethod());
        $this->assertEquals(HttpHandler::AUTH_TYPE_PUBLIC, $token->getAuth());
        $this->assertEquals('Content-type: ' . HttpHandler::MIME_TYPE_JSON, $token->getContentType());
    }
}
