<?php

namespace Checkout\Tests\Payments\Setups;

use Checkout\JsonSerializer;
use Checkout\Payments\Setups\Common\BillingDescriptor\PaymentSetupBillingDescriptor;
use Checkout\Payments\Setups\Common\Industry\AccommodationData;
use Checkout\Payments\Setups\Common\Industry\AccommodationHost;
use Checkout\Payments\Setups\Common\Industry\AirlineData;
use Checkout\Payments\Setups\Common\Industry\AirlineInsurance;
use Checkout\Payments\Setups\Common\Industry\AirlineInsurancePrice;
use Checkout\Payments\Setups\Common\Industry\Industry;
use Checkout\Payments\Setups\Common\Order\AmountAllocationCommission;
use Checkout\Payments\Setups\Common\Order\Order;
use Checkout\Payments\Setups\Common\Order\PaymentSetupAmountAllocation;
use Checkout\Payments\AccommodationAddress;
use Checkout\Payments\AccommodationGuest;
use Checkout\Payments\AccommodationRoom;
use Checkout\Payments\Ticket;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\Bacs;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolderType;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;
use Checkout\Payments\Setups\Common\PresentmentDetails\PaymentSetupPresentmentDetails;
use Checkout\Payments\Setups\Common\Terminal\PaymentSetupTerminal;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use PHPUnit\Framework\TestCase;

class PaymentSetupFieldsSerializationTest extends TestCase
{
    public function testSerializesBillingDescriptorPresentmentDetailsAndTerminal()
    {
        $request = new PaymentSetupRequest();

        $request->billing_descriptor = new PaymentSetupBillingDescriptor();
        $request->billing_descriptor->name = "Checkout.com";
        $request->billing_descriptor->city = "London";
        $request->billing_descriptor->reference = "ref_123";

        $request->presentment_details = new PaymentSetupPresentmentDetails();
        $request->presentment_details->amount = 110;
        $request->presentment_details->currency = "GBP";

        $request->terminal = new PaymentSetupTerminal();
        $request->terminal->id = "12345678";
        $request->terminal->local_date_time = "2026-06-01T10:00:00Z";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("Checkout.com", $decoded['billing_descriptor']['name']);
        $this->assertSame("London", $decoded['billing_descriptor']['city']);
        $this->assertSame("ref_123", $decoded['billing_descriptor']['reference']);

        $this->assertSame(110, $decoded['presentment_details']['amount']);
        $this->assertSame("GBP", $decoded['presentment_details']['currency']);

        $this->assertSame("12345678", $decoded['terminal']['id']);
        $this->assertSame("2026-06-01T10:00:00Z", $decoded['terminal']['local_date_time']);
    }

    public function testSerializesNewPaymentMethodConfigs()
    {
        $bacs = new Bacs();
        $bacs->instrument_id = "src_test";
        $bacs->account_number = "12345678";
        $bacs->bank_code = "050389";
        $bacs->country = "GB";
        $bacs->currency = "GBP";
        $bacs->allow_partial_match = true;
        $bacs->account_holder = new BacsAccountHolder();
        $bacs->account_holder->type = BacsAccountHolderType::$individual;
        $bacs->account_holder->first_name = "John";

        $cardPresent = new CardPresent();
        $cardPresent->entry_mode = "chip";
        $cardPresent->store_for_future_use = true;

        $payByBank = new PayByBank();
        $payByBank->bank_id = "bank_123";

        $paymentMethods = new PaymentMethods();
        $paymentMethods->bacs = $bacs;
        $paymentMethods->card_present = $cardPresent;
        $paymentMethods->pay_by_bank = $payByBank;
        $paymentMethods->stablecoin = new Stablecoin();

        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("src_test", $decoded['payment_methods']['bacs']['instrument_id']);
        $this->assertSame("050389", $decoded['payment_methods']['bacs']['bank_code']);
        $this->assertSame("GB", $decoded['payment_methods']['bacs']['country']);
        $this->assertTrue($decoded['payment_methods']['bacs']['allow_partial_match']);
        $this->assertSame("individual", $decoded['payment_methods']['bacs']['account_holder']['type']);
        $this->assertSame("John", $decoded['payment_methods']['bacs']['account_holder']['first_name']);

        $this->assertSame("chip", $decoded['payment_methods']['card_present']['entry_mode']);
        $this->assertTrue($decoded['payment_methods']['card_present']['store_for_future_use']);

        $this->assertSame("bank_123", $decoded['payment_methods']['pay_by_bank']['bank_id']);

        $this->assertArrayHasKey('stablecoin', $decoded['payment_methods']);
    }

    public function testSerializesOrderAmountAllocationsAndScalars()
    {
        $commission = new AmountAllocationCommission();
        $commission->amount = 100;
        $commission->percentage = 1.5;

        $allocation = new PaymentSetupAmountAllocation();
        $allocation->id = "ent_test";
        $allocation->amount = 1000;
        $allocation->reference = "order_123";
        $allocation->commission = $commission;

        $order = new Order();
        $order->amount_allocations = [$allocation];
        $order->invoice_id = "inv_123";
        $order->shipping_amount = 50;
        $order->surcharge_amount = 10;
        $order->tax_amount = 200;
        $order->tipping_amount = 100;

        $request = new PaymentSetupRequest();
        $request->order = $order;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $allocationJson = $decoded['order']['amount_allocations'][0];
        $this->assertSame("ent_test", $allocationJson['id']);
        $this->assertSame(1000, $allocationJson['amount']);
        $this->assertSame("order_123", $allocationJson['reference']);
        $this->assertSame(100, $allocationJson['commission']['amount']);
        $this->assertSame(1.5, $allocationJson['commission']['percentage']);

        $this->assertSame("inv_123", $decoded['order']['invoice_id']);
        $this->assertSame(50, $decoded['order']['shipping_amount']);
        $this->assertSame(10, $decoded['order']['surcharge_amount']);
        $this->assertSame(200, $decoded['order']['tax_amount']);
        $this->assertSame(100, $decoded['order']['tipping_amount']);
    }

    public function testSerializesAccommodationIndustryDataIncludingNewFields()
    {
        $address = new AccommodationAddress();
        $address->address_line1 = "1 Main Street";
        $address->zip = "SW1A 1AA";

        $guest = new AccommodationGuest();
        $guest->first_name = "Jane";
        $guest->last_name = "Smith";

        $room = new AccommodationRoom();
        $room->rate = "150.00";
        $room->number_of_nights_at_room_rate = "3";

        $host = new AccommodationHost();
        $host->total_reservation_count = 42;

        $accommodation = new AccommodationData();
        $accommodation->name = "Grand Hotel";
        $accommodation->booking_reference = "book_123";
        $accommodation->address = $address;
        $accommodation->number_of_rooms = 2;
        $accommodation->guests = [$guest];
        $accommodation->room = [$room];
        $accommodation->total_number_of_guests = 3;
        $accommodation->refundable = true;
        $accommodation->delivery_recipient = "jane.smith@example.com";
        $accommodation->host = $host;

        $industry = new Industry();
        $industry->accommodation_data = $accommodation;

        $request = new PaymentSetupRequest();
        $request->industry = $industry;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);
        $decodedAccommodation = $decoded['industry']['accommodation_data'];

        $this->assertSame("Grand Hotel", $decodedAccommodation['name']);
        $this->assertSame("book_123", $decodedAccommodation['booking_reference']);
        $this->assertSame("1 Main Street", $decodedAccommodation['address']['address_line1']);
        $this->assertSame(2, $decodedAccommodation['number_of_rooms']);
        $this->assertSame("Jane", $decodedAccommodation['guests'][0]['first_name']);
        $this->assertSame("150.00", $decodedAccommodation['room'][0]['rate']);
        $this->assertSame(3, $decodedAccommodation['total_number_of_guests']);
        $this->assertTrue($decodedAccommodation['refundable']);
        $this->assertSame("jane.smith@example.com", $decodedAccommodation['delivery_recipient']);
        $this->assertSame(42, $decodedAccommodation['host']['total_reservation_count']);
    }

    public function testSerializesAirlineIndustryDataIncludingNewFields()
    {
        $ticket = new Ticket();
        $ticket->number = "TCK123";
        $ticket->issuing_carrier_code = "BA";

        $price = new AirlineInsurancePrice();
        $price->amount = 25.5;
        $price->currency = "GBP";

        $insurance = new AirlineInsurance();
        $insurance->type = "travel";
        $insurance->company = "Acme Insurance";
        $insurance->price = $price;

        $airline = new AirlineData();
        $airline->ticket = $ticket;
        $airline->total_number_of_passengers = 2;
        $airline->travel_type = "international";
        $airline->trip_type = "round_trip";
        $airline->refundable = false;
        $airline->delivery_recipient = "jane.smith@example.com";
        $airline->ancillaries = "extra_baggage";
        $airline->insurance = $insurance;

        $industry = new Industry();
        $industry->airline_data = $airline;

        $request = new PaymentSetupRequest();
        $request->industry = $industry;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);
        $decodedAirline = $decoded['industry']['airline_data'];

        $this->assertSame("TCK123", $decodedAirline['ticket']['number']);
        $this->assertSame(2, $decodedAirline['total_number_of_passengers']);
        $this->assertSame("international", $decodedAirline['travel_type']);
        $this->assertSame("round_trip", $decodedAirline['trip_type']);
        $this->assertFalse($decodedAirline['refundable']);
        $this->assertSame("jane.smith@example.com", $decodedAirline['delivery_recipient']);
        $this->assertSame("extra_baggage", $decodedAirline['ancillaries']);
        $this->assertSame("travel", $decodedAirline['insurance']['type']);
        $this->assertSame("Acme Insurance", $decodedAirline['insurance']['company']);
        $this->assertSame(25.5, $decodedAirline['insurance']['price']['amount']);
        $this->assertSame("GBP", $decodedAirline['insurance']['price']['currency']);
    }

    public function testUnsetFieldsAreOmittedFromSerialization()
    {
        $decoded = json_decode((new JsonSerializer())->serialize(new PaymentSetupRequest()), true);

        $this->assertArrayNotHasKey('billing_descriptor', $decoded);
        $this->assertArrayNotHasKey('presentment_details', $decoded);
        $this->assertArrayNotHasKey('terminal', $decoded);
    }
}
