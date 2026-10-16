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
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodInitialization;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentMethodStatus;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppAction;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppActionType;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppAddress;
use Checkout\Payments\Setups\Common\PaymentMethods\CashApp\CashAppCustomerProfile;
use Checkout\Common\AccountHolderType;
use Checkout\Payments\PreferredSchema;
use Checkout\Payments\Setups\Common\Order\OrderSubMerchant;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePay;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePayTokenData;
use Checkout\Payments\Setups\Common\PaymentMethods\ApplePay\ApplePayTokenDataHeader;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\Card;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\CardDetails;
use Checkout\Payments\Setups\Common\PaymentMethods\Card\CardToken;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\CardAccountHolder;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\PaymentSetupCardScheme;
use Checkout\Payments\Setups\Common\PaymentMethods\Common\WalletToken;
use Checkout\Payments\Setups\Common\PaymentMethods\GooglePay\GooglePay;
use Checkout\Payments\Setups\Common\PaymentMethods\GooglePay\GooglePayTokenData;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrument;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrumentAction;
use Checkout\Payments\Setups\Common\PaymentMethods\Instrument\PaymentSetupInstrumentActionType;
use Checkout\Payments\Setups\Common\Settings\Settings;
use Checkout\Payments\Setups\Common\PaymentMethods\PayByBank\PayByBank;
use Checkout\Payments\Setups\Common\Risk\PaymentSetupRisk;
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

    const WALLET_TOKEN_ID = "tok_ubfj2q76miwundwlk72vxt2i7q";

    const GOOGLEPAY_SIGNATURE = "MEQCIH6Q4OwQ0jAceFEkGF0JID6sJNXxOEi4r+mA7biRxqBQAiAondqoUpU/"
        . "bdsrAOpZIsrHQS9nwiiNwOrr24RyPeHA0Q==";

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
        $paymentMethods->paybybank = $payByBank;
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

        $this->assertSame("bank_123", $decoded['payment_methods']['paybybank']['bank_id']);
        $this->assertArrayNotHasKey('pay_by_bank', $decoded['payment_methods']);

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

    public function testSerializesCustomerProfileSharingFalse()
    {
        $cashApp = new CashApp();
        $cashApp->initialization = "enabled";
        $cashApp->customer_profile_sharing = false;

        $decoded = json_decode((new JsonSerializer())->serialize($cashApp), true);

        // The serializer strips nulls only; an explicit false must still reach the API.
        $this->assertArrayHasKey("customer_profile_sharing", $decoded);
        $this->assertFalse($decoded["customer_profile_sharing"]);
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
        $this->assertStringNotContainsString('"administrative_district_level1"', $json);

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
                "device": {
                    "locale": "en_GB",
                    "fingerprint": "fp_abc123xyz",
                    "ipv4": "203.0.113.0",
                    "ipv6": "2001:db8:85a3::8a2e:370:7334",
                    "client": "web",
                    "os": "android"
                },
                "merchant_account": {
                    "id": "1234",
                    "registration_date": "2023-05-01T00:00:00.0000000",
                    "last_modified": "2023-05-01T00:00:00.0000000",
                    "returning_customer": true,
                    "first_transaction_date": "2023-09-15T00:00:00.0000000",
                    "last_transaction_date": "2025-03-28T00:00:00.0000000",
                    "total_order_count": 6,
                    "last_payment_amount": 55.99
                }
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
        $this->assertSame("fp_abc123xyz", $customer['device']['fingerprint']);
        $this->assertSame("203.0.113.0", $customer['device']['ipv4']);
        $this->assertSame("2001:db8:85a3::8a2e:370:7334", $customer['device']['ipv6']);
        $this->assertSame(DeviceClient::$web, $customer['device']['client']);
        $this->assertSame(DeviceOs::$android, $customer['device']['os']);
        $this->assertSame("1234", $customer['merchant_account']['id']);
        $this->assertSame("2023-05-01T00:00:00.0000000", $customer['merchant_account']['registration_date']);
        $this->assertSame("2023-05-01T00:00:00.0000000", $customer['merchant_account']['last_modified']);
        $this->assertTrue($customer['merchant_account']['returning_customer']);
        $this->assertSame("2023-09-15T00:00:00.0000000", $customer['merchant_account']['first_transaction_date']);
        $this->assertSame("2025-03-28T00:00:00.0000000", $customer['merchant_account']['last_transaction_date']);
        $this->assertSame(6, $customer['merchant_account']['total_order_count']);
        $this->assertSame(55.99, $customer['merchant_account']['last_payment_amount']);
    }

    public function testPaymentMethodStatusAndInitializationConstantsMatchTheSpec()
    {
        $this->assertSame("unavailable", PaymentMethodStatus::$unavailable);
        $this->assertSame("action_required", PaymentMethodStatus::$action_required);
        $this->assertSame("ready", PaymentMethodStatus::$ready);
        $this->assertSame("initialization_required", PaymentMethodStatus::$initialization_required);
        $this->assertSame("invalid", PaymentMethodStatus::$invalid);
        $this->assertSame("disabled", PaymentMethodInitialization::$disabled);
        $this->assertSame("enabled", PaymentMethodInitialization::$enabled);
    }

    public function testSerializesPayByBankUnderLiteralPaybybankKey()
    {
        $payByBank = new PayByBank();
        $payByBank->bank_id = "ob-natwest";

        $paymentMethods = new PaymentMethods();
        $paymentMethods->paybybank = $payByBank;

        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $json = (new JsonSerializer())->serialize($request);

        $this->assertStringContainsString('"paybybank":{"bank_id":"ob-natwest"}', $json);
        $this->assertStringNotContainsString('pay_by_bank', $json);
    }

    public function testDeserializesPayByBankActionFromResponse()
    {
        $json = '{
            "id": "ps_2Un4Ld0Qm8Dj6kXHm0LqT0bK5mL",
            "payment_methods": {
                "paybybank": {
                    "status": "action_required",
                    "flags": [],
                    "bank_id": "ob-natwest",
                    "action": {
                        "type": "select_bank",
                        "banks": [
                            {
                                "bank_id": "ob-natwest",
                                "display_name": "NatWest",
                                "logo_url": "https://cdn.token.io/banks/natwest.png",
                                "available": true
                            }
                        ]
                    }
                }
            }
        }';

        $response = (new JsonSerializer())->deserialize($json);
        $payByBank = $response['payment_methods']['paybybank'];

        $this->assertSame("action_required", $payByBank['status']);
        $this->assertSame([], $payByBank['flags']);
        $this->assertSame("ob-natwest", $payByBank['bank_id']);
        $this->assertSame("select_bank", $payByBank['action']['type']);
        $this->assertCount(1, $payByBank['action']['banks']);
        $bank = $payByBank['action']['banks'][0];
        $this->assertSame("ob-natwest", $bank['bank_id']);
        $this->assertSame("NatWest", $bank['display_name']);
        $this->assertSame("https://cdn.token.io/banks/natwest.png", $bank['logo_url']);
        $this->assertTrue($bank['available']);
    }

    public function testSerializesRiskEnabled()
    {
        $serializer = new JsonSerializer();
        foreach ([true, false] as $enabled) {
            $request = new PaymentSetupRequest();
            $request->risk = new PaymentSetupRisk();
            $request->risk->enabled = $enabled;

            $decoded = json_decode($serializer->serialize($request), true);

            $this->assertSame(["enabled" => $enabled], $decoded['risk']);
        }
    }

    public function testRiskIsOmittedWhenUnset()
    {
        $decoded = json_decode((new JsonSerializer())->serialize(new PaymentSetupRequest()), true);

        $this->assertArrayNotHasKey('risk', $decoded);
    }

    public function testCardRoundTripKeepsEveryProperty()
    {
        $token = new CardToken();
        $token->id = self::WALLET_TOKEN_ID;

        $details = new CardDetails();
        $details->number = "4242424242424242";
        $details->last4 = "4242";
        $details->bin = "424242";
        $details->scheme = "visa";
        $details->expiry_month = 12;
        $details->expiry_year = 2030;
        $details->name = "John Smith";
        $details->cvv = "100";
        $details->stored = false;
        $details->expires_on = new DateTime("2026-12-31T23:59:59+00:00");
        $details->allow_update = true;

        $card = new Card();
        $card->status = PaymentMethodStatus::$ready;
        $card->flags = ["missing_cvv"];
        $card->token = $token;
        $card->details = $details;
        $card->store_for_future_use = true;
        $card->phone = $this->buildPhone();
        $card->account_holder = $this->buildCardAccountHolder();
        $card->schemes = [PaymentSetupCardScheme::$visa, PaymentSetupCardScheme::$cartes_bancaires];
        $card->preferred_scheme = PreferredSchema::$cartes_bancaires;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->card = $card;
        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($request);
        $decoded = $serializer->deserialize($json)['payment_methods']['card'];

        $this->assertStringContainsString('"card":{', $json);
        $this->assertStringContainsString('"store_for_future_use":true', $json);
        $this->assertStringContainsString('"preferred_scheme":"cartes_bancaires"', $json);
        $this->assertStringContainsString('"account_name_inquiry":true', $json);
        $this->assertStringNotContainsString('initialization', $json);
        $this->assertCount(9, $decoded);
        $this->assertSame("ready", $decoded['status']);
        $this->assertSame(["missing_cvv"], $decoded['flags']);
        $this->assertSame(["id" => self::WALLET_TOKEN_ID], $decoded['token']);
        $this->assertSame(
            [
                "number" => "4242424242424242",
                "last4" => "4242",
                "bin" => "424242",
                "scheme" => "visa",
                "expiry_month" => 12,
                "expiry_year" => 2030,
                "name" => "John Smith",
                "cvv" => "100",
                "stored" => false,
                "expires_on" => "2026-12-31T23:59:59+0000",
                "allow_update" => true,
            ],
            $decoded['details']
        );
        $this->assertTrue($decoded['store_for_future_use']);
        $this->assertSame(["country_code" => "+44", "number" => "207 946 0000"], $decoded['phone']);
        $this->assertSame($this->expectedCardAccountHolder(), $decoded['account_holder']);
        $this->assertSame(["visa", "cartes_bancaires"], $decoded['schemes']);
        $this->assertSame("cartes_bancaires", $decoded['preferred_scheme']);
    }

    public function testApplePayRoundTripKeepsEveryPropertyWithoutDetails()
    {
        $header = new ApplePayTokenDataHeader();
        $header->ephemeral_public_key = "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAE";
        $header->public_key_hash = "tqYV+tmG9aMh+l/K6cicUnPqkb1gUiLjSTM9gEz6Nl0=";
        $header->transaction_id = "3cee89679130a4b2617c76118a1790";

        $tokenData = new ApplePayTokenData();
        $tokenData->version = "EC_v1";
        $tokenData->data = "t7GeajLB9skXB6QSWfEC";
        $tokenData->signature = "MIAGCSqGSIb3DQEHAqCAMIACAQExDzANBglghkgBZQMEAgEFADCABgkqhkiG9w0BBwEAAKCAMIID";
        $tokenData->header = $header;

        $applePay = new ApplePay();
        $applePay->status = PaymentMethodStatus::$action_required;
        $applePay->flags = [];
        $applePay->token_data = $tokenData;
        $applePay->token = $this->buildWalletToken();
        $applePay->store_for_future_use = false;
        $applePay->phone = $this->buildPhone();
        $applePay->account_holder = $this->buildCardAccountHolder();
        $applePay->schemes = [PaymentSetupCardScheme::$amex, PaymentSetupCardScheme::$mada];
        $applePay->preferred_scheme = PreferredSchema::$visa;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->applepay = $applePay;
        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($request);
        $decoded = $serializer->deserialize($json)['payment_methods']['applepay'];

        $this->assertStringContainsString('"applepay":{', $json);
        $this->assertStringNotContainsString('apple_pay', $json);
        $this->assertStringContainsString('"token_data":{', $json);
        $this->assertStringContainsString('"ephemeral_public_key":', $json);
        $this->assertStringNotContainsString('"details"', $json);
        $this->assertStringNotContainsString('initialization', $json);
        $this->assertCount(9, $decoded);
        $this->assertSame("action_required", $decoded['status']);
        $this->assertSame([], $decoded['flags']);
        $this->assertSame(
            [
                "version" => "EC_v1",
                "data" => "t7GeajLB9skXB6QSWfEC",
                "signature" => "MIAGCSqGSIb3DQEHAqCAMIACAQExDzANBglghkgBZQMEAgEFADCABgkqhkiG9w0BBwEAAKCAMIID",
                "header" => [
                    "ephemeral_public_key" => "MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAE",
                    "public_key_hash" => "tqYV+tmG9aMh+l/K6cicUnPqkb1gUiLjSTM9gEz6Nl0=",
                    "transaction_id" => "3cee89679130a4b2617c76118a1790",
                ],
            ],
            $decoded['token_data']
        );
        $this->assertSame($this->expectedWalletToken(), $decoded['token']);
        $this->assertFalse($decoded['store_for_future_use']);
        $this->assertSame(["country_code" => "+44", "number" => "207 946 0000"], $decoded['phone']);
        $this->assertSame($this->expectedCardAccountHolder(), $decoded['account_holder']);
        $this->assertSame(["amex", "mada"], $decoded['schemes']);
        $this->assertSame("visa", $decoded['preferred_scheme']);
    }

    public function testGooglePayRoundTripKeepsEveryPropertyWithoutDetails()
    {
        $tokenData = new GooglePayTokenData();
        $tokenData->protocol_version = "ECv2";
        $tokenData->signature = self::GOOGLEPAY_SIGNATURE;
        $tokenData->signed_message = '{"encryptedMessage":"ZW5jcnlwdGVk","ephemeralPublicKey":"BOdoXP","tag":"WhBT"}';
        $tokenData->tokenization_key = "pk_sbox_xxxxxxxxxxxxxxxxxxxxxxxxxx";

        $googlePay = new GooglePay();
        $googlePay->status = PaymentMethodStatus::$ready;
        $googlePay->flags = ["token_expired"];
        $googlePay->token_data = $tokenData;
        $googlePay->token = $this->buildWalletToken();
        $googlePay->store_for_future_use = true;
        $googlePay->phone = $this->buildPhone();
        $googlePay->account_holder = $this->buildCardAccountHolder();
        $googlePay->schemes = [PaymentSetupCardScheme::$mastercard, PaymentSetupCardScheme::$visa];
        $googlePay->preferred_scheme = PreferredSchema::$mastercard;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->googlepay = $googlePay;
        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($request);
        $decoded = $serializer->deserialize($json)['payment_methods']['googlepay'];

        $this->assertStringContainsString('"googlepay":{', $json);
        $this->assertStringNotContainsString('google_pay', $json);
        $this->assertStringContainsString('"signed_message":', $json);
        $this->assertStringNotContainsString('signedMessage', $json);
        $this->assertStringNotContainsString('"details"', $json);
        $this->assertStringNotContainsString('initialization', $json);
        $this->assertCount(9, $decoded);
        $this->assertSame("ready", $decoded['status']);
        $this->assertSame(["token_expired"], $decoded['flags']);
        $this->assertSame(
            [
                "protocol_version" => "ECv2",
                "signature" => self::GOOGLEPAY_SIGNATURE,
                "signed_message" => '{"encryptedMessage":"ZW5jcnlwdGVk","ephemeralPublicKey":"BOdoXP","tag":"WhBT"}',
                "tokenization_key" => "pk_sbox_xxxxxxxxxxxxxxxxxxxxxxxxxx",
            ],
            $decoded['token_data']
        );
        $this->assertSame($this->expectedWalletToken(), $decoded['token']);
        $this->assertTrue($decoded['store_for_future_use']);
        $this->assertSame(["country_code" => "+44", "number" => "207 946 0000"], $decoded['phone']);
        $this->assertSame($this->expectedCardAccountHolder(), $decoded['account_holder']);
        $this->assertSame(["mastercard", "visa"], $decoded['schemes']);
        $this->assertSame("mastercard", $decoded['preferred_scheme']);
    }

    public function testInstrumentAndAutoConfirmRoundTrip()
    {
        $action = new PaymentSetupInstrumentAction();
        $action->type = PaymentSetupInstrumentActionType::$set_instrument;

        $instrument = new PaymentSetupInstrument();
        $instrument->status = PaymentMethodStatus::$ready;
        $instrument->flags = [];
        $instrument->id = "src_wmlfc3zttb4uzmk6snpwb43jbi";
        $instrument->type = "bacs";
        $instrument->action = $action;

        $paymentMethods = new PaymentMethods();
        $paymentMethods->auto_confirm = "card";
        $paymentMethods->instrument = $instrument;
        $request = new PaymentSetupRequest();
        $request->payment_methods = $paymentMethods;

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($request);
        $decoded = $serializer->deserialize($json)['payment_methods'];

        $this->assertStringContainsString('"auto_confirm":"card"', $json);
        $this->assertStringContainsString('"instrument":{', $json);
        $this->assertStringNotContainsString('autoConfirm', $json);
        $this->assertStringNotContainsString('initialization', $json);
        $this->assertSame("card", $decoded['auto_confirm']);
        $this->assertSame(
            [
                "status" => "ready",
                "flags" => [],
                "id" => "src_wmlfc3zttb4uzmk6snpwb43jbi",
                "type" => "bacs",
                "action" => ["type" => "set_instrument"],
            ],
            $decoded['instrument']
        );
    }

    public function testSettingsRoundTripKeepsAllFourProperties()
    {
        $settings = new Settings();
        $settings->success_url = "https://example.com/payments/success";
        $settings->failure_url = "https://example.com/payments/fail";
        $settings->capture = false;
        $settings->excluded_payment_methods = ["card", "klarna", "stcpay"];

        $request = new PaymentSetupRequest();
        $request->settings = $settings;

        $serializer = new JsonSerializer();
        $json = $serializer->serialize($request);
        $decoded = $serializer->deserialize($json);

        // capture defaults to true on the API, so an explicit false must still be sent.
        $this->assertStringContainsString('"capture":false', $json);
        $this->assertStringContainsString('"excluded_payment_methods":["card","klarna","stcpay"]', $json);
        $this->assertSame(
            [
                "success_url" => "https://example.com/payments/success",
                "failure_url" => "https://example.com/payments/fail",
                "capture" => false,
                "excluded_payment_methods" => ["card", "klarna", "stcpay"],
            ],
            $decoded['settings']
        );
    }

    public function testSubMerchantSendsNumberOfSales()
    {
        $subMerchant = new OrderSubMerchant();
        $subMerchant->id = "SUB12345";
        $subMerchant->product_category = "Electronics";
        $subMerchant->number_of_sales = 500;
        $subMerchant->registration_date = new DateTime("2023-01-15");

        $order = new Order();
        $order->sub_merchants = [$subMerchant];
        $request = new PaymentSetupRequest();
        $request->order = $order;

        $json = (new JsonSerializer())->serialize($request);
        $decoded = json_decode($json, true);

        $this->assertStringContainsString('"number_of_sales":500', $json);
        $this->assertStringNotContainsString('number_of_trades', $json);
        $this->assertSame(
            [
                "id" => "SUB12345",
                "product_category" => "Electronics",
                "number_of_sales" => 500,
                "registration_date" => "2023-01-15",
            ],
            $decoded['order']['sub_merchants'][0]
        );
    }

    public function testDeprecatedNumberOfTradesIsSentAsNumberOfSales()
    {
        $subMerchant = new OrderSubMerchant();
        $subMerchant->id = "SUB12345";
        $subMerchant->number_of_trades = 500;

        $order = new Order();
        $order->sub_merchants = [$subMerchant];
        $request = new PaymentSetupRequest();
        $request->order = $order;

        $json = (new JsonSerializer())->serialize($request);
        $decoded = json_decode($json, true);

        $this->assertStringNotContainsString('number_of_trades', $json);
        $this->assertSame(["id" => "SUB12345", "number_of_sales" => 500], $decoded['order']['sub_merchants'][0]);
    }

    public function testNumberOfSalesWinsOverDeprecatedNumberOfTrades()
    {
        $subMerchant = new OrderSubMerchant();
        $subMerchant->number_of_trades = 10;
        $subMerchant->number_of_sales = 500;

        $json = (new JsonSerializer())->serialize($subMerchant);

        $this->assertSame('{"number_of_sales":500}', $json);
    }

    public function testMetadataSerializesStringNumberAndBooleanValues()
    {
        $request = new PaymentSetupRequest();
        $request->metadata = ["coupon_code" => "NY2018", "partner_id" => 123989, "vip" => true];

        $json = (new JsonSerializer())->serialize($request);
        $decoded = json_decode($json, true);

        $this->assertStringContainsString('"metadata":{"coupon_code":"NY2018","partner_id":123989,"vip":true}', $json);
        $this->assertSame(["coupon_code" => "NY2018", "partner_id" => 123989, "vip" => true], $decoded['metadata']);
    }

    public function testDeserializesPaymentSetupSwaggerExampleWithNewFields()
    {
        $json = '{
            "id": "ps_2Un4Ld0Qm8Dj6kXHm0LqT0bK5mL",
            "processing_channel_id": "pc_q4dbxom5jbgudnjzjpz7j2z6uq",
            "amount": 10000,
            "currency": "GBP",
            "payment_methods": {
                "auto_confirm": "card",
                "instrument": {
                    "status": "ready",
                    "flags": [],
                    "id": "src_wmlfc3zttb4uzmk6snpwb43jbi",
                    "type": "bacs",
                    "action": { "type": "set_instrument" }
                },
                "card": {
                    "status": "ready",
                    "flags": [],
                    "token": { "id": "' . self::WALLET_TOKEN_ID . '" },
                    "details": {
                        "last4": "4242",
                        "bin": "424242",
                        "scheme": "visa",
                        "expiry_month": 12,
                        "expiry_year": 2030,
                        "expires_on": "2026-12-31T23:59:59Z",
                        "allow_update": true
                    },
                    "phone": { "country_code": "+44", "number": "207 946 0000" },
                    "account_holder": {
                        "type": "individual",
                        "first_name": "John",
                        "last_name": "Smith",
                        "middle_name": "Paul",
                        "company_name": "Acme Ltd",
                        "account_name_inquiry": true
                    },
                    "schemes": ["visa", "mastercard", "cartes_bancaires"],
                    "preferred_scheme": "visa"
                },
                "applepay": {
                    "status": "ready",
                    "token": { "id": "' . self::WALLET_TOKEN_ID . '", "expires_on": "2026-12-31T23:59:59Z" },
                    "schemes": ["visa", "mastercard"],
                    "preferred_scheme": "visa"
                },
                "googlepay": {
                    "status": "ready",
                    "token": { "id": "' . self::WALLET_TOKEN_ID . '", "expires_on": "2026-12-31T23:59:59Z" },
                    "schemes": ["visa", "mastercard"],
                    "preferred_scheme": "mastercard"
                }
            },
            "settings": {
                "success_url": "https://example.com/payments/success",
                "failure_url": "https://example.com/payments/fail",
                "capture": true,
                "excluded_payment_methods": ["card", "klarna", "stcpay"]
            },
            "order": {
                "sub_merchants": [
                    {
                        "id": "SUB12345",
                        "product_category": "Electronics",
                        "number_of_sales": 500,
                        "registration_date": "2023-01-15T00:00:00.0000000"
                    }
                ]
            },
            "metadata": { "coupon_code": "NY2018", "partner_id": 123989 }
        }';

        $response = (new JsonSerializer())->deserialize($json);
        $paymentMethods = $response['payment_methods'];

        $this->assertSame("card", $paymentMethods['auto_confirm']);
        $this->assertSame("src_wmlfc3zttb4uzmk6snpwb43jbi", $paymentMethods['instrument']['id']);
        $this->assertSame("bacs", $paymentMethods['instrument']['type']);
        $this->assertSame(
            PaymentSetupInstrumentActionType::$set_instrument,
            $paymentMethods['instrument']['action']['type']
        );

        $card = $paymentMethods['card'];
        $this->assertSame(PaymentMethodStatus::$ready, $card['status']);
        $this->assertSame(self::WALLET_TOKEN_ID, $card['token']['id']);
        $this->assertSame("4242", $card['details']['last4']);
        $this->assertSame("424242", $card['details']['bin']);
        $this->assertSame("visa", $card['details']['scheme']);
        $this->assertSame(12, $card['details']['expiry_month']);
        $this->assertSame(2030, $card['details']['expiry_year']);
        $this->assertSame("2026-12-31T23:59:59Z", $card['details']['expires_on']);
        $this->assertTrue($card['details']['allow_update']);
        $this->assertSame("+44", $card['phone']['country_code']);
        $this->assertSame(AccountHolderType::$individual, $card['account_holder']['type']);
        $this->assertSame("Paul", $card['account_holder']['middle_name']);
        $this->assertTrue($card['account_holder']['account_name_inquiry']);
        $this->assertSame(
            [
                PaymentSetupCardScheme::$visa,
                PaymentSetupCardScheme::$mastercard,
                PaymentSetupCardScheme::$cartes_bancaires,
            ],
            $card['schemes']
        );
        $this->assertSame(PreferredSchema::$visa, $card['preferred_scheme']);

        $walletSchemes = ['applepay' => PreferredSchema::$visa, 'googlepay' => PreferredSchema::$mastercard];
        foreach ($walletSchemes as $key => $scheme) {
            $wallet = $paymentMethods[$key];
            $this->assertArrayNotHasKey('details', $wallet);
            $this->assertSame(self::WALLET_TOKEN_ID, $wallet['token']['id']);
            $this->assertSame("2026-12-31T23:59:59Z", $wallet['token']['expires_on']);
            $this->assertSame(["visa", "mastercard"], $wallet['schemes']);
            $this->assertSame($scheme, $wallet['preferred_scheme']);
        }

        $this->assertSame("https://example.com/payments/success", $response['settings']['success_url']);
        $this->assertSame("https://example.com/payments/fail", $response['settings']['failure_url']);
        $this->assertTrue($response['settings']['capture']);
        $this->assertSame(["card", "klarna", "stcpay"], $response['settings']['excluded_payment_methods']);

        $subMerchant = $response['order']['sub_merchants'][0];
        $this->assertSame("SUB12345", $subMerchant['id']);
        $this->assertSame("Electronics", $subMerchant['product_category']);
        $this->assertSame(500, $subMerchant['number_of_sales']);
        $this->assertSame("2023-01-15T00:00:00.0000000", $subMerchant['registration_date']);

        $this->assertSame(["coupon_code" => "NY2018", "partner_id" => 123989], $response['metadata']);
    }

    public function testCardSchemeAndInstrumentActionConstantsMatchTheSpec()
    {
        $expected = [
            "accel", "amex", "cartes_bancaires", "china_union_pay", "diners", "discover", "jcb", "mada",
            "maestro", "mastercard", "nyce", "omannet", "pulse", "shazam", "star", "upi", "visa",
        ];
        $actual = array_values((new \ReflectionClass(PaymentSetupCardScheme::class))->getStaticProperties());

        $this->assertSame($expected, $actual);
        $this->assertSame("set_instrument", PaymentSetupInstrumentActionType::$set_instrument);
    }

    private function buildPhone()
    {
        $phone = new Phone();
        $phone->country_code = "+44";
        $phone->number = "207 946 0000";
        return $phone;
    }

    private function buildCardAccountHolder()
    {
        $accountHolder = new CardAccountHolder();
        $accountHolder->type = AccountHolderType::$corporate;
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "Smith";
        $accountHolder->middle_name = "Paul";
        $accountHolder->company_name = "Acme Ltd";
        $accountHolder->account_name_inquiry = true;
        return $accountHolder;
    }

    private function expectedCardAccountHolder()
    {
        return [
            "type" => "corporate",
            "first_name" => "John",
            "last_name" => "Smith",
            "middle_name" => "Paul",
            "company_name" => "Acme Ltd",
            "account_name_inquiry" => true,
        ];
    }

    private function buildWalletToken()
    {
        $token = new WalletToken();
        $token->id = self::WALLET_TOKEN_ID;
        $token->expires_on = new DateTime("2026-12-31T23:59:59+00:00");
        return $token;
    }

    private function expectedWalletToken()
    {
        return ["id" => self::WALLET_TOKEN_ID, "expires_on" => "2026-12-31T23:59:59+0000"];
    }
}
