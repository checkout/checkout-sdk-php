<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\Issuing\Cards\Create\PhysicalCardRequest;
use Checkout\Issuing\Cards\Create\VirtualCardRequest;
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class CardScheduledActivationDateSerializationTest extends TestCase
{
    /**
     * @var JsonSerializer
     */
    private $serializer;

    /**
     * @before
     */
    public function init()
    {
        $this->serializer = new JsonSerializer();
    }

    public function testVirtualCardRequestSerializesScheduledActivationDate()
    {
        $request = new VirtualCardRequest();
        $request->cardholder_id = "crh_d3ozhf43pcq2xbldn2g45qnb44";
        $request->card_product_id = "pro_3fn5gcelkktzzhfu4n22tz2mjq";
        $request->reference = "X-123456-N11";
        $request->scheduled_activation_date = "2026-06-01T10:00Z";

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("2026-06-01T10:00Z", $decoded["scheduled_activation_date"]);
        $this->assertArrayNotHasKey("activation_date", $decoded);
    }

    public function testPhysicalCardRequestSerializesScheduledActivationDate()
    {
        $request = new PhysicalCardRequest();
        $request->cardholder_id = "crh_d3ozhf43pcq2xbldn2g45qnb44";
        $request->card_product_id = "pro_3fn5gcelkktzzhfu4n22tz2mjq";
        $request->scheduled_activation_date = "2026-06-01";

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("2026-06-01", $decoded["scheduled_activation_date"]);
        $this->assertArrayNotHasKey("activation_date", $decoded);
    }

    public function testUpdateCardRequestSerializesScheduledActivationDate()
    {
        $request = new UpdateCardRequest();
        $request->scheduled_activation_date = "2026-06-01T10:00Z";

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame(["scheduled_activation_date"], array_keys($decoded));
        $this->assertArrayNotHasKey("activation_date", $decoded);
    }

    public function testUpdateCardRequestSerializesRevocationDate()
    {
        $request = new UpdateCardRequest();
        $request->revocation_date = "2027-03-12";

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("2027-03-12", $decoded["revocation_date"]);
    }

    public function testUpdateCardRequestSerializesEveryProperty()
    {
        $request = new UpdateCardRequest();
        $request->reference = "X-123456-N11";
        $request->expiry_month = 6;
        $request->expiry_year = 2030;
        $request->scheduled_activation_date = "2026-06-01T10:00Z";
        $request->revocation_date = "2027-03-12";

        $decoded = json_decode($this->serializer->serialize($request), true);

        $this->assertSame("X-123456-N11", $decoded["reference"]);
        $this->assertSame(6, $decoded["expiry_month"]);
        $this->assertSame(2030, $decoded["expiry_year"]);
        $this->assertSame("2026-06-01T10:00Z", $decoded["scheduled_activation_date"]);
        $this->assertSame("2027-03-12", $decoded["revocation_date"]);
    }

    public function testUpdateCardRequestRoundTrip()
    {
        $request = new UpdateCardRequest();
        $request->scheduled_activation_date = "2026-06-01T10:00Z";
        $request->revocation_date = "2027-03-12";

        $json = $this->serializer->serialize($request);
        $decoded = $this->serializer->deserialize($json);

        $this->assertSame(json_decode($json, true), $decoded);
        $this->assertSame("2026-06-01T10:00Z", $decoded["scheduled_activation_date"]);
    }

    public function testUpdateCardRequestFromSwaggerExample()
    {
        $json = '{'
            . '"reference":"X-123456-N11",'
            . '"expiry_month":6,'
            . '"expiry_year":2030,'
            . '"revocation_date":"2027-03-12",'
            . '"scheduled_activation_date":"2026-06-01T10:00Z"'
            . '}';

        $decoded = $this->serializer->deserialize($json);

        $this->assertSame("2027-03-12", $decoded["revocation_date"]);
        $this->assertSame("2026-06-01T10:00Z", $decoded["scheduled_activation_date"]);
    }
}
