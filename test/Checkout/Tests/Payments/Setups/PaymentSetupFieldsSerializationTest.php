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
use Checkout\Payments\Setups\Common\Industry\PaymentSetupAccommodationAddress;
use Checkout\Payments\AccommodationGuest;
use Checkout\Payments\Setups\Common\Industry\PaymentSetupAccommodationRoom;
use Checkout\Payments\Ticket;
use Checkout\Common\Phone;
use Checkout\Payments\Setups\Common\Customer\Customer;
use Checkout\Payments\Setups\Common\Customer\Device;
use Checkout\Payments\Setups\Common\Customer\DeviceClient;
use Checkout\Payments\Setups\Common\Customer\DeviceOs;
use Checkout\Payments\Setups\Common\Customer\Email;
use Checkout\Payments\Setups\Common\Customer\MerchantAccount;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\Bacs;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Bacs\BacsAccountHolderType;
use Checkout\Payments\Setups\Common\PaymentMethods\CardPresent\CardPresent;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashApp;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppAction;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppActionType;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppAddress;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppCustomerProfile;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\PaymentMethods\PaymentMethods;
use Checkout\Payments\Setups\Common\PaymentMethods\Stablecoin\Stablecoin;
use Checkout\Payments\Setups\Common\PresentmentDetails\PaymentSetupPresentmentDetails;
use Checkout\Payments\Setups\Common\Terminal\PaymentSetupTerminal;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;
use DateTime;
use PHPUnit\Framework\TestCase;

class PaymentSetupFieldsSerializationTest extends TestCase
{
    const CASHAPP_REDIRECT_URL = "https://sandbox.api.cash.app/customer-request/v1/requests/"
        . "GRR_f5xg6wrxhtv3p4w24g0wrexa/interstitial?validity_token=bap03y";

    const CASHAPP_CUSTOMER_ID = "CST_AYVkuLzfsRqEhf4OyQFxQNv22m7IjNFjO6f2J5CDE2nxAC4-21wJ2H8_2kvsdIsDZMN4";

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
        $address = new PaymentSetupAccommodationAddress();
        $address->address_line1 = "1 Main Street";
        $address->city = "London";
        $address->state = "Greater London";
        $address->country = "GB";
        $address->zip = "SW1A 1AA";

        $guest = new AccommodationGuest();
        $guest->first_name = "Jane";
        $guest->last_name = "Smith";

        $room = new PaymentSetupAccommodationRoom();
        $room->rate = 42.3;
        $room->number_of_nights = 3;
        $room->type = "deluxe";

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
        $industry->accommodation = [$accommodation];

        $request = new PaymentSetupRequest();
        $request->industry = $industry;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);
        $decodedAccommodation = $decoded['industry']['accommodation'][0];

        $this->assertSame("Grand Hotel", $decodedAccommodation['name']);
        $this->assertSame("book_123", $decodedAccommodation['booking_reference']);
        $this->assertSame("1 Main Street", $decodedAccommodation['address']['address_line1']);
        $this->assertSame(2, $decodedAccommodation['number_of_rooms']);
        $this->assertSame("Jane", $decodedAccommodation['guests'][0]['first_name']);
        // rate is a number on this schema, not a string: the specification's own example is
        // 42.3. Verified by round-trip against the sandbox, where a string "150.00" came back
        // as 150. A whole float like 150.00 json-encodes to 150, so the fixture uses a
        // fractional value to keep the type visible in the assertion.
        $this->assertSame(42.3, $decodedAccommodation['room'][0]['rate']);
        // number_of_nights and type were unsendable while this reused the payments
        // AccommodationRoom, which declares number_of_nights_at_room_rate and no type.
        $this->assertSame(3, $decodedAccommodation['room'][0]['number_of_nights']);
        $this->assertSame("deluxe", $decodedAccommodation['room'][0]['type']);
        $this->assertArrayNotHasKey('number_of_nights_at_room_rate', $decodedAccommodation['room'][0]);
        // city, state and country were unsendable while this reused the payments
        // AccommodationAddress, which declares only address_line1 and zip.
        $this->assertSame("London", $decodedAccommodation['address']['city']);
        $this->assertSame("Greater London", $decodedAccommodation['address']['state']);
        $this->assertSame("GB", $decodedAccommodation['address']['country']);
        // industry.accommodation is an array under that exact key; accommodation_data was
        // discarded wholesale by the API.
        $this->assertArrayHasKey('accommodation', $decoded['industry']);
        $this->assertArrayNotHasKey('accommodation_data', $decoded['industry']);
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
        $industry->airline = [$airline];

        $request = new PaymentSetupRequest();
        $request->industry = $industry;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);
        $decodedAirline = $decoded['industry']['airline'][0];

        $this->assertArrayHasKey('airline', $decoded['industry']);
        $this->assertArrayNotHasKey('airline_data', $decoded['industry']);

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

    public function testSerializesCashAppUnderLiteralKeyWithMerchantFields()
    {
        $cashApp = new CashApp();
        $cashApp->initialization = "enabled";
        $cashApp->customer_profile_sharing = true;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->cashapp = $cashApp;

        $request = new PaymentSetupRequest();
        $request->processing_channel_id = "pc_aaaaaaaaaaaaaaaaaaaaaaaaaa";
        $request->amount = 1000;
        $request->currency = "USD";
        $request->payment_methods = $paymentMethods;

        $json = (new JsonSerializer())->serialize($request);
        $decoded = json_decode($json, true);

        $this->assertStringContainsString('"cashapp":', $json);
        $this->assertStringNotContainsString('cash_app', $json);
        $this->assertStringNotContainsString('cashApp', $json);
        $this->assertStringNotContainsString('customerProfileSharing', $json);
        $this->assertSame(
            ["initialization" => "enabled", "customer_profile_sharing" => true],
            $decoded['payment_methods']['cashapp']
        );
    }

    public function testSerializesEveryDeviceClientValue()
    {
        $serializer = new JsonSerializer();
        $device = new Device();

        $device->client = DeviceClient::$web;
        $this->assertSame('{"client":"web"}', $serializer->serialize($device));

        $device->client = DeviceClient::$mobile_web;
        $this->assertSame('{"client":"mobile_web"}', $serializer->serialize($device));

        $device->client = DeviceClient::$app;
        $this->assertSame('{"client":"app"}', $serializer->serialize($device));
    }

    public function testSerializesEveryDeviceOsValue()
    {
        $serializer = new JsonSerializer();
        $device = new Device();

        $device->os = DeviceOs::$android;
        $this->assertSame('{"os":"android"}', $serializer->serialize($device));

        $device->os = DeviceOs::$ios;
        $this->assertSame('{"os":"ios"}', $serializer->serialize($device));
    }

    public function testSerializesAllDeviceFields()
    {
        $device = new Device();
        $device->locale = "en_US";
        $device->fingerprint = "fp_abc123xyz";
        $device->ipv4 = "203.0.113.0";
        $device->ipv6 = "2001:db8:85a3::8a2e:370:7334";
        $device->client = DeviceClient::$web;
        $device->os = DeviceOs::$android;

        $customer = new Customer();
        $customer->device = $device;
        $request = new PaymentSetupRequest();
        $request->customer = $customer;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame(
            [
                "locale" => "en_US",
                "fingerprint" => "fp_abc123xyz",
                "ipv4" => "203.0.113.0",
                "ipv6" => "2001:db8:85a3::8a2e:370:7334",
                "client" => "web",
                "os" => "android",
            ],
            $decoded['customer']['device']
        );
    }

    public function testSerializesDeviceWithOnlyLocale()
    {
        $device = new Device();
        $device->locale = "en_US";

        $this->assertSame('{"locale":"en_US"}', (new JsonSerializer())->serialize($device));
    }

    public function testCashAppRoundTripKeepsEveryProperty()
    {
        $address = new CashAppAddress();
        $address->address_line_1 = "123 Main St";
        $address->address_line_2 = "Apt 2";
        $address->address_line_3 = "Floor 3";
        $address->locality = "Springfield";
        $address->sublocality = "Downtown";
        $address->administrative_district_level_1 = "IL";
        $address->postal_code = "62701";
        $address->country = "US";

        $profile = new CashAppCustomerProfile();
        $profile->customer_id = self::CASHAPP_CUSTOMER_ID;
        $profile->cashtag = '$CASHTAG_C_TOKEN';
        $profile->reference_id = "value";
        $profile->full_name = "John Middle Doe";
        $profile->given_name = "John";
        $profile->middle_name = "Middle";
        $profile->family_name = "Doe";
        $profile->suffix = "Jr.";
        $profile->birth_date = "1990-01-01T00:00:00.0000000";
        $profile->address = $address;
        $profile->phone_number = "5555555555";
        $profile->email_address = "cash@cash.com";
        $profile->customer_since = "1970-01-18T12:46:04.8000000+00:00";

        $action = new CashAppAction();
        $action->type = CashAppActionType::$redirect;
        $action->redirect_url = self::CASHAPP_REDIRECT_URL;

        $cashApp = new CashApp();
        $cashApp->status = "action_required";
        $cashApp->flags = [];
        $cashApp->initialization = "enabled";
        $cashApp->customer_profile_sharing = true;
        $cashApp->reference = "ORDER-99";
        $cashApp->action = $action;
        $cashApp->customer_profile = $profile;

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($cashApp);
        $decoded = $serializer->deserialize($json);

        $this->assertStringContainsString('"address_line_1":', $json);
        $this->assertStringContainsString('"address_line_2":', $json);
        $this->assertStringContainsString('"address_line_3":', $json);
        $this->assertStringContainsString('"administrative_district_level_1":', $json);
        $this->assertStringNotContainsString('"address_line1"', $json);

        $this->assertSame("action_required", $decoded['status']);
        $this->assertSame([], $decoded['flags']);
        $this->assertSame("enabled", $decoded['initialization']);
        $this->assertTrue($decoded['customer_profile_sharing']);
        $this->assertSame("ORDER-99", $decoded['reference']);
        $this->assertSame("redirect", $decoded['action']['type']);
        $this->assertSame(self::CASHAPP_REDIRECT_URL, $decoded['action']['redirect_url']);

        $decodedProfile = $decoded['customer_profile'];
        $this->assertCount(13, $decodedProfile);
        $this->assertSame(self::CASHAPP_CUSTOMER_ID, $decodedProfile['customer_id']);
        $this->assertSame('$CASHTAG_C_TOKEN', $decodedProfile['cashtag']);
        $this->assertSame("value", $decodedProfile['reference_id']);
        $this->assertSame("John Middle Doe", $decodedProfile['full_name']);
        $this->assertSame("John", $decodedProfile['given_name']);
        $this->assertSame("Middle", $decodedProfile['middle_name']);
        $this->assertSame("Doe", $decodedProfile['family_name']);
        $this->assertSame("Jr.", $decodedProfile['suffix']);
        $this->assertSame("1990-01-01T00:00:00.0000000", $decodedProfile['birth_date']);
        $this->assertSame("5555555555", $decodedProfile['phone_number']);
        $this->assertSame("cash@cash.com", $decodedProfile['email_address']);
        $this->assertSame("1970-01-18T12:46:04.8000000+00:00", $decodedProfile['customer_since']);
        $this->assertSame(
            [
                "address_line_1" => "123 Main St",
                "address_line_2" => "Apt 2",
                "address_line_3" => "Floor 3",
                "locality" => "Springfield",
                "sublocality" => "Downtown",
                "administrative_district_level_1" => "IL",
                "postal_code" => "62701",
                "country" => "US",
            ],
            $decodedProfile['address']
        );
    }

    public function testDeserializesCashAppSwaggerExampleResponse()
    {
        $json = '{
            "id": "ps_2Un4Ld0Qm8Dj6kXHm0LqT0bK5mL",
            "payment_methods": {
                "cashapp": {
                    "status": "action_required",
                    "flags": [],
                    "initialization": "enabled",
                    "customer_profile_sharing": true,
                    "reference": "ORDER-99",
                    "action": {
                        "type": "redirect",
                        "redirect_url": "' . self::CASHAPP_REDIRECT_URL . '"
                    },
                    "customer_profile": {
                        "customer_id": "' . self::CASHAPP_CUSTOMER_ID . '",
                        "cashtag": "$CASHTAG_C_TOKEN",
                        "reference_id": "value",
                        "full_name": "John Middle Doe",
                        "given_name": "John",
                        "middle_name": "Middle",
                        "family_name": "Doe",
                        "suffix": "Jr.",
                        "birth_date": "1990-01-01T00:00:00.0000000",
                        "address": {
                            "address_line_1": "123 Main St",
                            "address_line_2": "Apt 2",
                            "address_line_3": "Floor 3",
                            "locality": "Springfield",
                            "sublocality": "Downtown",
                            "administrative_district_level_1": "IL",
                            "postal_code": "62701",
                            "country": "US"
                        },
                        "phone_number": "5555555555",
                        "email_address": "cash@cash.com",
                        "customer_since": "1970-01-18T12:46:04.8000000+00:00"
                    }
                }
            },
            "customer": {
                "device": {
                    "locale": "en_US",
                    "fingerprint": "fp_abc123xyz",
                    "ipv4": "203.0.113.0",
                    "ipv6": "2001:db8:85a3::8a2e:370:7334",
                    "client": "web",
                    "os": "android"
                }
            }
        }';

        $response = (new JsonSerializer())->deserialize($json);
        $cashApp = $response['payment_methods']['cashapp'];

        $this->assertSame("action_required", $cashApp['status']);
        $this->assertSame([], $cashApp['flags']);
        $this->assertSame("enabled", $cashApp['initialization']);
        $this->assertTrue($cashApp['customer_profile_sharing']);
        $this->assertSame("ORDER-99", $cashApp['reference']);
        $this->assertSame(CashAppActionType::$redirect, $cashApp['action']['type']);
        $this->assertSame(self::CASHAPP_REDIRECT_URL, $cashApp['action']['redirect_url']);

        $profile = $cashApp['customer_profile'];
        $this->assertCount(13, $profile);
        $this->assertSame(self::CASHAPP_CUSTOMER_ID, $profile['customer_id']);
        $this->assertSame('$CASHTAG_C_TOKEN', $profile['cashtag']);
        $this->assertSame("value", $profile['reference_id']);
        $this->assertSame("John Middle Doe", $profile['full_name']);
        $this->assertSame("John", $profile['given_name']);
        $this->assertSame("Middle", $profile['middle_name']);
        $this->assertSame("Doe", $profile['family_name']);
        $this->assertSame("Jr.", $profile['suffix']);
        $this->assertSame("1990-01-01T00:00:00.0000000", $profile['birth_date']);
        $this->assertSame("5555555555", $profile['phone_number']);
        $this->assertSame("cash@cash.com", $profile['email_address']);
        $this->assertSame("1970-01-18T12:46:04.8000000+00:00", $profile['customer_since']);

        $address = $profile['address'];
        $this->assertCount(8, $address);
        $this->assertSame("123 Main St", $address['address_line_1']);
        $this->assertSame("Apt 2", $address['address_line_2']);
        $this->assertSame("Floor 3", $address['address_line_3']);
        $this->assertSame("Springfield", $address['locality']);
        $this->assertSame("Downtown", $address['sublocality']);
        $this->assertSame("IL", $address['administrative_district_level_1']);
        $this->assertSame("62701", $address['postal_code']);
        $this->assertSame("US", $address['country']);

        $device = $response['customer']['device'];
        $this->assertSame("en_US", $device['locale']);
        $this->assertSame("fp_abc123xyz", $device['fingerprint']);
        $this->assertSame("203.0.113.0", $device['ipv4']);
        $this->assertSame("2001:db8:85a3::8a2e:370:7334", $device['ipv6']);
        $this->assertSame(DeviceClient::$web, $device['client']);
        $this->assertSame(DeviceOs::$android, $device['os']);
    }

    public function testCustomerRoundTripKeepsAllEightProperties()
    {
        $email = new Email();
        $email->address = "johnsmith@example.com";
        $email->verified = true;

        $phone = new Phone();
        $phone->country_code = "+44";
        $phone->number = "207 946 0000";

        $device = new Device();
        $device->locale = "en_GB";
        $device->fingerprint = "fp_abc123xyz";
        $device->ipv4 = "203.0.113.0";
        $device->ipv6 = "2001:db8:85a3::8a2e:370:7334";
        $device->client = DeviceClient::$app;
        $device->os = DeviceOs::$ios;

        $merchantAccount = new MerchantAccount();
        $merchantAccount->id = "1234";
        $merchantAccount->registration_date = new DateTime("2023-05-01");
        $merchantAccount->last_modified = new DateTime("2023-05-01");
        $merchantAccount->returning_customer = true;
        $merchantAccount->first_transaction_date = new DateTime("2023-09-15");
        $merchantAccount->last_transaction_date = new DateTime("2025-03-28");
        $merchantAccount->total_order_count = 6;
        $merchantAccount->last_payment_amount = 55;

        $customer = new Customer();
        $customer->email = $email;
        $customer->name = "John Smith";
        $customer->phone = $phone;
        $customer->device = $device;
        $customer->merchant_account = $merchantAccount;
        $customer->id = "cus_123456789";
        $customer->country = "GB";
        $customer->tax_number = "GB123456789";

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($customer);
        $decoded = $serializer->deserialize($json);

        $this->assertStringContainsString('"tax_number":"GB123456789"', $json);
        $this->assertStringNotContainsString('taxNumber', $json);
        $this->assertCount(8, $decoded);
        $this->assertSame("cus_123456789", $decoded['id']);
        $this->assertSame("GB", $decoded['country']);
        $this->assertSame("GB123456789", $decoded['tax_number']);
        $this->assertSame("John Smith", $decoded['name']);
        $this->assertSame(["address" => "johnsmith@example.com", "verified" => true], $decoded['email']);
        $this->assertSame(["country_code" => "+44", "number" => "207 946 0000"], $decoded['phone']);
        $this->assertSame(
            [
                "locale" => "en_GB",
                "fingerprint" => "fp_abc123xyz",
                "ipv4" => "203.0.113.0",
                "ipv6" => "2001:db8:85a3::8a2e:370:7334",
                "client" => "app",
                "os" => "ios",
            ],
            $decoded['device']
        );
        $this->assertSame(
            [
                "id" => "1234",
                "registration_date" => "2023-05-01",
                "last_modified" => "2023-05-01",
                "returning_customer" => true,
                "first_transaction_date" => "2023-09-15",
                "last_transaction_date" => "2025-03-28",
                "total_order_count" => 6,
                "last_payment_amount" => 55,
            ],
            $decoded['merchant_account']
        );
    }

    public function testDeserializesCustomerSwaggerExample()
    {
        $json = '{
            "customer": {
                "country": "GB",
                "id": "cus_123456789",
                "email": { "address": "johnsmith@example.com", "verified": true },
                "name": "John Smith",
                "tax_number": "GB123456789",
                "phone": { "country_code": "+44", "number": "207 946 0000" },
                "device": { "locale": "en_GB" }
            }
        }';

        $customer = (new JsonSerializer())->deserialize($json)['customer'];

        $this->assertSame("GB", $customer['country']);
        $this->assertSame("cus_123456789", $customer['id']);
        $this->assertSame("johnsmith@example.com", $customer['email']['address']);
        $this->assertTrue($customer['email']['verified']);
        $this->assertSame("John Smith", $customer['name']);
        $this->assertSame("GB123456789", $customer['tax_number']);
        $this->assertSame("+44", $customer['phone']['country_code']);
        $this->assertSame("207 946 0000", $customer['phone']['number']);
        $this->assertSame("en_GB", $customer['device']['locale']);
    }
}
