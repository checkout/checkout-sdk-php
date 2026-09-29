<?php

namespace Checkout\Tests\Payments;

use Checkout\JsonSerializer;
use Checkout\Payments\ProcessingSettings;
use PHPUnit\Framework\TestCase;

/**
 * The swagger types tax_amount, discount_amount, shipping_amount, shipping_tax_amount,
 * duty_amount and original_order_amount as `number`, not `integer`, and the live API honours
 * that: POST /payments with "tax_amount": 10.5 returns 201 and GET /payments/{id} echoes 10.5
 * back.
 *
 * PHP has no runtime type enforcement on these properties, so the `@var int` doc never broke
 * anything, but it was the documented contract and told merchants a fractional amount was
 * invalid. On the same data Java threw a JsonSyntaxException and Go failed the entire response;
 * see those SDKs' tests.
 */
class ProcessingFractionalAmountTest extends TestCase
{
    public function testShouldSerializeFractionalProcessingAmounts()
    {
        $settings = new ProcessingSettings();
        $settings->tax_amount = 10.5;
        $settings->discount_amount = 0.25;
        $settings->shipping_amount = 3.75;
        $settings->shipping_tax_amount = 1.5;
        $settings->duty_amount = 2.05;
        $settings->original_order_amount = 99.99;

        $body = json_decode((new JsonSerializer())->serialize($settings), true);

        $this->assertSame(10.5, $body["tax_amount"]);
        $this->assertSame(0.25, $body["discount_amount"]);
        $this->assertSame(3.75, $body["shipping_amount"]);
        $this->assertSame(1.5, $body["shipping_tax_amount"]);
        $this->assertSame(2.05, $body["duty_amount"]);
        $this->assertSame(99.99, $body["original_order_amount"]);
    }

    public function testShouldSerializeWholeProcessingAmountsUnchanged()
    {
        $settings = new ProcessingSettings();
        $settings->tax_amount = 3000;

        $body = json_decode((new JsonSerializer())->serialize($settings), true);

        $this->assertSame(3000, $body["tax_amount"]);
    }
}
