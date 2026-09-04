<?php

namespace Checkout\Tests\Apm\Bacs;

use Checkout\Apm\Bacs\BacsNotificationRequest;
use Checkout\Apm\Bacs\BacsNotificationType;
use Checkout\Common\Currency;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

/**
 * Schema validation tests for BacsNotificationRequest against the swagger schema of
 * POST /apms/bacs/notifications. Covers all 10 properties.
 */
class BacsSerializationTest extends TestCase
{
    private function serializer()
    {
        return new JsonSerializer();
    }

    private function fullRequest()
    {
        $request = new BacsNotificationRequest();
        $request->source_id = "src_wmlfc3zyhqzehihu7giusaaawu";
        $request->notification_type = BacsNotificationType::$advance_notice;
        $request->collection_date = "2026-07-15";
        $request->amount = 4999;
        $request->currency = Currency::$GBP;
        $request->reference = "INV-12345";
        $request->customer_email = "customer@example.com";
        $request->billing_descriptor = "CHECKOUT";
        $request->support_email = "support@test.com";
        $request->support_phone = "+447700900123";
        return $request;
    }

    /**
     * @test
     */
    public function shouldSerializeEveryPropertyUsingTheSwaggerExample()
    {
        $decoded = json_decode($this->serializer()->serialize($this->fullRequest()), true);

        $this->assertSame("src_wmlfc3zyhqzehihu7giusaaawu", $decoded["source_id"]);
        $this->assertSame("advance_notice", $decoded["notification_type"]);
        $this->assertSame("2026-07-15", $decoded["collection_date"]);
        $this->assertSame(4999, $decoded["amount"]);
        $this->assertSame("GBP", $decoded["currency"]);
        $this->assertSame("INV-12345", $decoded["reference"]);
        $this->assertSame("customer@example.com", $decoded["customer_email"]);
        $this->assertSame("CHECKOUT", $decoded["billing_descriptor"]);
        $this->assertSame("support@test.com", $decoded["support_email"]);
        $this->assertSame("+447700900123", $decoded["support_phone"]);
        $this->assertCount(10, $decoded);
    }

    /**
     * @test
     */
    public function shouldOmitTheTwoOptionalPropertiesWhenUnset()
    {
        $request = $this->fullRequest();
        $request->reference = null;
        $request->support_phone = null;

        $decoded = json_decode($this->serializer()->serialize($request), true);

        $this->assertArrayNotHasKey("reference", $decoded);
        $this->assertArrayNotHasKey("support_phone", $decoded);
        $this->assertCount(8, $decoded);
    }

    /**
     * @test
     */
    public function shouldCarryTheSingleNotificationTypeValue()
    {
        $this->assertSame("advance_notice", BacsNotificationType::$advance_notice);
    }
}
