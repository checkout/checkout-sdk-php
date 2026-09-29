<?php

namespace Checkout\Tests\Payments\Contexts;

use Checkout\JsonSerializer;
use Checkout\Payments\AccommodationData;
use Checkout\Payments\AccommodationRoom;
use Checkout\Payments\BillingPlan;
use Checkout\Payments\Contexts\PaymentContextsAirlineData;
use Checkout\Payments\Contexts\PaymentContextsFlightLegDetails;
use Checkout\Payments\Contexts\PaymentContextsPassenger;
use Checkout\Payments\Contexts\PaymentContextsProcessing;
use Checkout\Payments\Contexts\PaymentContextsTicket;
use Checkout\Payments\PassengerAddress;
use DateTime;
use PHPUnit\Framework\TestCase;

/**
 * Serialization tests for the payment contexts airline and accommodation sub-tree.
 *
 * PHP serializes public properties under the name they are written with, so a misnamed property
 * is a silently wrong wire key. Three were wrong here: $tickets and $passengers (the spec names
 * them ticket and passenger, singular) and $billing_plan (the spec names it plan). All three
 * meant the value never reached the gateway.
 */
class PaymentContextsAirlineSerializationTest extends TestCase
{
    public function testSerializesTicketAndPassengerUnderTheKeysTheSpecNames()
    {
        $airline = new PaymentContextsAirlineData();

        $airline->ticket = new PaymentContextsTicket();
        $airline->ticket->number = "045-21351455613";
        $airline->ticket->travel_package_indicator = "B";

        $passenger = new PaymentContextsPassenger();
        $passenger->first_name = "John";
        $passenger->last_name = "White";
        $airline->passenger = $passenger;

        $leg = new PaymentContextsFlightLegDetails();
        $leg->flight_number = "101";
        $leg->class_of_travelling = "J";
        $airline->flight_leg_details = array($leg);

        $json = (new JsonSerializer())->serialize($airline);
        $decoded = json_decode($json, true);

        // The keys the specification declares, in specification order.
        $this->assertSame(array('ticket', 'passenger', 'flight_leg_details'), array_keys($decoded));

        // The keys the SDK used to send, which the API does not define.
        $this->assertArrayNotHasKey('tickets', $decoded);
        $this->assertArrayNotHasKey('passengers', $decoded);

        // ticket is a single object and passenger an object for one passenger, never arrays.
        $this->assertSame(
            array('number' => '045-21351455613', 'travel_package_indicator' => 'B'),
            $decoded['ticket']
        );
        $this->assertSame(array('first_name' => 'John', 'last_name' => 'White'), $decoded['passenger']);
        $this->assertSame(
            array('flight_number' => '101', 'class_of_travelling' => 'J'),
            $decoded['flight_leg_details'][0]
        );
    }

    /**
     * POST /payment-contexts rejects the array form of passenger with 422 passenger_required, so
     * one passenger serializes as an object.
     */
    public function testSerializesASinglePassengerAsAnObject()
    {
        $passenger = new PaymentContextsPassenger();
        $passenger->first_name = "John";
        $passenger->address = new PassengerAddress();
        $passenger->address->country = "GB";

        $airline = new PaymentContextsAirlineData();
        $airline->passenger = $passenger;

        $json = (new JsonSerializer())->serialize($airline);

        $this->assertStringContainsString('"passenger":{', $json);
        $this->assertStringNotContainsString('"passenger":[', $json);
        $this->assertStringContainsString('"address":{"country":"GB"}', $json);
    }

    public function testOmitsPassengerWhenNotSet()
    {
        $airline = new PaymentContextsAirlineData();
        $airline->ticket = new PaymentContextsTicket();
        $airline->ticket->number = "045";

        $json = (new JsonSerializer())->serialize($airline);

        $this->assertStringNotContainsString('passenger', $json);
    }

    public function testSerializesTicketDatesAsShortDates()
    {
        $ticket = new PaymentContextsTicket();
        $ticket->number = "045";
        $ticket->issue_date = new DateTime("2023-05-20 08:15:00");

        $json = (new JsonSerializer())->serialize($ticket);

        $this->assertStringContainsString('"issue_date":"2023-05-20"', $json);
        $this->assertStringNotContainsString('08:15', $json);
    }

    /**
     * The five fields PaymentContextsProcessing was missing, plus the renamed plan.
     */
    public function testSerializesTheProcessingFieldsTheSpecDeclares()
    {
        $processing = new PaymentContextsProcessing();

        $processing->plan = new BillingPlan();
        $processing->plan->type = "MERCHANT_INITIATED_BILLING";
        $processing->plan->skip_shipping_address = true;

        $processing->discount_amount = 5;
        $processing->shipping_amount = 300;
        $processing->tax_amount = 3000;
        $processing->invoice_id = "INV-1";
        $processing->brand_name = "Acme Corporation";
        $processing->locale = "en-US";
        $processing->custom_payment_method_ids = array("cpm_001", "cpm_002");

        $accommodation = new AccommodationData();
        $accommodation->name = "The Sea View Hotel";
        $accommodation->state = "FL";
        $accommodation->country = "USA";
        $room = new AccommodationRoom();
        $room->rate = "70";
        $room->number_of_nights_at_room_rate = "3";
        $accommodation->room = array($room);
        $processing->accommodation_data = array($accommodation);

        $decoded = json_decode((new JsonSerializer())->serialize($processing), true);

        // plan, not billing_plan: the old name was a key the API does not define.
        $this->assertArrayHasKey('plan', $decoded);
        $this->assertArrayNotHasKey('billing_plan', $decoded);
        $this->assertSame(
            array('type' => 'MERCHANT_INITIATED_BILLING', 'skip_shipping_address' => true),
            $decoded['plan']
        );

        // Every property the fixture sets, asserted.
        $this->assertSame(5, $decoded['discount_amount']);
        $this->assertSame(300, $decoded['shipping_amount']);
        $this->assertSame(3000, $decoded['tax_amount']);
        $this->assertSame("INV-1", $decoded['invoice_id']);
        $this->assertSame("Acme Corporation", $decoded['brand_name']);
        $this->assertSame("en-US", $decoded['locale']);
        $this->assertSame(array('cpm_001', 'cpm_002'), $decoded['custom_payment_method_ids']);

        $accommodation = $decoded['accommodation_data'][0];
        $this->assertSame("The Sea View Hotel", $accommodation['name']);
        $this->assertSame("FL", $accommodation['state']);
        $this->assertSame("USA", $accommodation['country']);
        $this->assertSame(
            array('rate' => '70', 'number_of_nights_at_room_rate' => '3'),
            $accommodation['room'][0]
        );
    }
}
