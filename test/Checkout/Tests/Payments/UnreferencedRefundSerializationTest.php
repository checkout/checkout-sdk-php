<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountHolder;
use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\AccountHolderIdentificationType;
use Checkout\Common\AccountHolderType;
use Checkout\Common\Address;
use Checkout\Common\Currency;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use Checkout\Payments\Destination\PaymentRequestCardDestination;
use Checkout\Payments\Destination\PaymentRequestIdDestination;
use Checkout\Payments\Destination\PaymentRequestNetworkTokenDestination;
use Checkout\Payments\Destination\PaymentRequestTokenDestination;
use Checkout\Payments\NetworkTokenType;
use Checkout\Payments\PaymentPurpose;
use Checkout\Payments\Request\CardPayoutProcessing;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\PaymentSegment;
use Checkout\Payments\Request\Source\PayoutRequestCurrencyAccountSource;
use Checkout\Payments\Request\UnreferencedRefundRequest;
use PHPUnit\Framework\TestCase;

class UnreferencedRefundSerializationTest extends TestCase
{
    /**
     * Spec-shaped unreferenced refund request, built from the UnreferencedRefundRequest property
     * examples (the schema has no request-level example).
     */
    const REQUEST_FIXTURE = <<<'JSON'
{
  "source": {
    "type": "currency_account",
    "id": "ca_y3oqhf46pyzuxjbcn2giaqnb44"
  },
  "destination": {
    "type": "card",
    "number": "4242424242424242",
    "expiry_month": 6,
    "expiry_year": 2030,
    "account_holder": {
      "type": "individual",
      "first_name": "John",
      "middle_name": "Paul",
      "last_name": "Smith",
      "billing_address": {
        "address_line1": "123 High St.",
        "address_line2": "Flat 456",
        "city": "London",
        "state": "GB",
        "zip": "SW1A 1AA",
        "country": "GB"
      },
      "phone": { "country_code": "+44", "number": "4155552671" },
      "identification": {
        "type": "passport",
        "number": "E12345678",
        "issuing_country": "GB",
        "date_of_expiry": "2030-01-01"
      },
      "email": "john.smith@example.com",
      "date_of_birth": "1985-05-15",
      "country_of_birth": "GB"
    }
  },
  "instruction": {
    "funds_transfer_type": "MI",
    "purpose": "family_support"
  },
  "amount": 120,
  "currency": "GBP",
  "payment_type": "UnreferencedRefund",
  "processing_channel_id": "pc_q4dbxom5jbgudnjzjpz7j2z6uq",
  "reference": "ORD-5023-4E89",
  "metadata": { "coupon_code": "NY2018", "partner_id": 123989 },
  "previous_payment_id": "pay_fun26akvvjjerahhctaq2uzhu4",
  "segment": {
    "brand": "Acme Corporation",
    "business_category": "Inward Payments",
    "market": "UK"
  },
  "processing": { "processing_speed": "fast" }
}
JSON;

    const REQUEST_KEYS = array(
        "source", "destination", "instruction", "amount", "currency", "payment_type",
        "processing_channel_id", "reference", "metadata", "previous_payment_id", "segment", "processing"
    );

    const RICH_INDIVIDUAL_KEYS = array(
        "type", "first_name", "middle_name", "last_name", "billing_address", "phone",
        "identification", "email", "date_of_birth", "country_of_birth"
    );

    private function richAccountHolder()
    {
        $address = new Address();
        $address->address_line1 = "123 High St.";
        $address->address_line2 = "Flat 456";
        $address->city = "London";
        $address->state = "GB";
        $address->zip = "SW1A 1AA";
        $address->country = "GB";

        $phone = new Phone();
        $phone->country_code = "+44";
        $phone->number = "4155552671";

        $identification = new AccountHolderIdentification();
        $identification->type = AccountHolderIdentificationType::$passport;
        $identification->number = "E12345678";
        $identification->issuing_country = "GB";
        $identification->date_of_expiry = "2030-01-01";

        $accountHolder = new AccountHolder();
        $accountHolder->type = AccountHolderType::$individual;
        $accountHolder->first_name = "John";
        $accountHolder->middle_name = "Paul";
        $accountHolder->last_name = "Smith";
        $accountHolder->billing_address = $address;
        $accountHolder->phone = $phone;
        $accountHolder->identification = $identification;
        $accountHolder->email = "john.smith@example.com";
        $accountHolder->date_of_birth = "1985-05-15";
        $accountHolder->country_of_birth = "GB";
        return $accountHolder;
    }

    private function cardDestination()
    {
        $destination = new PaymentRequestCardDestination();
        $destination->number = "4242424242424242";
        $destination->expiry_month = 6;
        $destination->expiry_year = 2030;
        $destination->account_holder = $this->richAccountHolder();
        return $destination;
    }

    private function fullRequest()
    {
        $source = new PayoutRequestCurrencyAccountSource();
        $source->id = "ca_y3oqhf46pyzuxjbcn2giaqnb44";

        $instruction = new PaymentInstruction();
        $instruction->funds_transfer_type = "MI";
        $instruction->purpose = PaymentPurpose::$family_support;

        $segment = new PaymentSegment();
        $segment->brand = "Acme Corporation";
        $segment->business_category = "Inward Payments";
        $segment->market = "UK";

        $processing = new CardPayoutProcessing();
        $processing->processing_speed = "fast";

        $request = new UnreferencedRefundRequest();
        $request->source = $source;
        $request->destination = $this->cardDestination();
        $request->instruction = $instruction;
        $request->amount = 120;
        $request->currency = Currency::$GBP;
        $request->processing_channel_id = "pc_q4dbxom5jbgudnjzjpz7j2z6uq";
        $request->reference = "ORD-5023-4E89";
        $request->metadata = array("coupon_code" => "NY2018", "partner_id" => 123989);
        $request->previous_payment_id = "pay_fun26akvvjjerahhctaq2uzhu4";
        $request->segment = $segment;
        $request->processing = $processing;
        return $request;
    }

    private function serializeToArray($object)
    {
        return json_decode((new JsonSerializer())->serialize($object), true);
    }

    public function testConstructorSetsPaymentTypeLiteral()
    {
        $this->assertSame("UnreferencedRefund", (new UnreferencedRefundRequest())->payment_type);
        $this->assertSame(
            array("payment_type" => "UnreferencedRefund"),
            $this->serializeToArray(new UnreferencedRefundRequest())
        );
    }

    public function testRequestSerializesLiteralWireKeys()
    {
        $decoded = $this->serializeToArray($this->fullRequest());

        $this->assertEqualsCanonicalizing(self::REQUEST_KEYS, array_keys($decoded));
        foreach (array_keys($decoded) as $key) {
            $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $key);
        }
        $this->assertSame(array("type", "id"), array_keys($decoded["source"]));
        $this->assertSame("currency_account", $decoded["source"]["type"]);
        $this->assertEqualsCanonicalizing(array("funds_transfer_type", "purpose"), array_keys($decoded["instruction"]));
        $this->assertEqualsCanonicalizing(array("brand", "business_category", "market"), array_keys($decoded["segment"]));
        $this->assertSame(array("processing_speed"), array_keys($decoded["processing"]));
    }

    public function testRequestMatchesSpecFixture()
    {
        $expected = json_decode(self::REQUEST_FIXTURE, true);

        $decoded = $this->serializeToArray($this->fullRequest());

        $this->assertEquals($expected, $decoded);
        $this->assertSame(120, $decoded["amount"]);
        $this->assertSame(6, $decoded["destination"]["expiry_month"]);
        $this->assertSame(2030, $decoded["destination"]["expiry_year"]);
    }

    public function testCardDestinationNestedUnderDestination()
    {
        $destination = $this->serializeToArray($this->fullRequest())["destination"];

        $this->assertEqualsCanonicalizing(
            array("type", "number", "expiry_month", "expiry_year", "account_holder"),
            array_keys($destination)
        );
        $this->assertSame("card", $destination["type"]);
        $this->assertEqualsCanonicalizing(self::RICH_INDIVIDUAL_KEYS, array_keys($destination["account_holder"]));
    }

    public function testNetworkTokenDestinationNestedUnderDestination()
    {
        $networkToken = new PaymentRequestNetworkTokenDestination();
        $networkToken->token = "4543474002249996";
        $networkToken->expiry_month = 6;
        $networkToken->expiry_year = 2025;
        $networkToken->token_type = NetworkTokenType::$vts;
        $networkToken->cryptogram = "hv8mUFzPzRZoCAAAAAEQBDMAAAA=";
        $networkToken->eci = "05";
        $networkToken->account_holder = $this->richAccountHolder();

        $request = $this->fullRequest();
        $request->destination = $networkToken;

        $destination = $this->serializeToArray($request)["destination"];

        $this->assertEqualsCanonicalizing(
            array("type", "token", "expiry_month", "expiry_year", "token_type", "cryptogram", "eci", "account_holder"),
            array_keys($destination)
        );
        $this->assertSame("network_token", $destination["type"]);
        $this->assertSame("4543474002249996", $destination["token"]);
        $this->assertSame(6, $destination["expiry_month"]);
        $this->assertSame(2025, $destination["expiry_year"]);
        $this->assertSame("vts", $destination["token_type"]);
        $this->assertSame("hv8mUFzPzRZoCAAAAAEQBDMAAAA=", $destination["cryptogram"]);
        $this->assertSame("05", $destination["eci"]);
        $this->assertEqualsCanonicalizing(self::RICH_INDIVIDUAL_KEYS, array_keys($destination["account_holder"]));
    }

    public function testIdDestinationNestedUnderDestinationWithRichAccountHolder()
    {
        $id = new PaymentRequestIdDestination();
        $id->id = "src_wmlfc3zyhqzehihu7giusaaawu";
        $id->account_holder = $this->richAccountHolder();

        $request = $this->fullRequest();
        $request->destination = $id;

        $destination = $this->serializeToArray($request)["destination"];

        $this->assertEqualsCanonicalizing(array("type", "id", "account_holder"), array_keys($destination));
        $this->assertSame("id", $destination["type"]);
        $this->assertSame("src_wmlfc3zyhqzehihu7giusaaawu", $destination["id"]);
        $this->assertEqualsCanonicalizing(self::RICH_INDIVIDUAL_KEYS, array_keys($destination["account_holder"]));
    }

    public function testIdDestinationOmitsUnsetAccountHolder()
    {
        $id = new PaymentRequestIdDestination();
        $id->id = "src_wmlfc3zyhqzehihu7giusaaawu";

        $request = $this->fullRequest();
        $request->destination = $id;

        $this->assertSame(
            array("type" => "id", "id" => "src_wmlfc3zyhqzehihu7giusaaawu"),
            $this->serializeToArray($request)["destination"]
        );
    }

    public function testTokenDestinationNestedUnderDestinationWithRichAccountHolder()
    {
        $token = new PaymentRequestTokenDestination();
        $token->token = "tok_ubfj2q76miwundwlk72vxt2i7q";
        $token->account_holder = $this->richAccountHolder();

        $request = $this->fullRequest();
        $request->destination = $token;

        $destination = $this->serializeToArray($request)["destination"];

        $this->assertEqualsCanonicalizing(array("type", "token", "account_holder"), array_keys($destination));
        $this->assertSame("token", $destination["type"]);
        $this->assertSame("tok_ubfj2q76miwundwlk72vxt2i7q", $destination["token"]);
        $this->assertEqualsCanonicalizing(self::RICH_INDIVIDUAL_KEYS, array_keys($destination["account_holder"]));
    }

    public function testTokenDestinationOmitsUnsetAccountHolder()
    {
        $token = new PaymentRequestTokenDestination();
        $token->token = "tok_ubfj2q76miwundwlk72vxt2i7q";

        $request = $this->fullRequest();
        $request->destination = $token;

        $this->assertSame(
            array("type" => "token", "token" => "tok_ubfj2q76miwundwlk72vxt2i7q"),
            $this->serializeToArray($request)["destination"]
        );
    }

    public function testRequestOmitsUnsetOptionalFields()
    {
        $request = $this->fullRequest();
        $request->instruction = null;
        $request->reference = null;
        $request->metadata = null;
        $request->previous_payment_id = null;
        $request->segment = null;
        $request->processing = null;

        $this->assertEqualsCanonicalizing(
            array("source", "destination", "amount", "currency", "payment_type", "processing_channel_id"),
            array_keys($this->serializeToArray($request))
        );
    }

    public function testRequestRoundTrip()
    {
        $request = $this->fullRequest();
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($request));

        $this->assertSame($request->source->type, $decoded["source"]["type"]);
        $this->assertSame($request->source->id, $decoded["source"]["id"]);
        $this->assertSame($request->destination->type, $decoded["destination"]["type"]);
        $this->assertSame($request->destination->number, $decoded["destination"]["number"]);
        $this->assertSame($request->destination->expiry_month, $decoded["destination"]["expiry_month"]);
        $this->assertSame($request->destination->expiry_year, $decoded["destination"]["expiry_year"]);

        $holder = $request->destination->account_holder;
        $decodedHolder = $decoded["destination"]["account_holder"];
        $this->assertSame($holder->type, $decodedHolder["type"]);
        $this->assertSame($holder->first_name, $decodedHolder["first_name"]);
        $this->assertSame($holder->middle_name, $decodedHolder["middle_name"]);
        $this->assertSame($holder->last_name, $decodedHolder["last_name"]);
        $this->assertSame(get_object_vars($holder->billing_address), $decodedHolder["billing_address"]);
        $this->assertSame(get_object_vars($holder->phone), $decodedHolder["phone"]);
        $this->assertSame(get_object_vars($holder->identification), $decodedHolder["identification"]);
        $this->assertSame($holder->email, $decodedHolder["email"]);
        $this->assertSame($holder->date_of_birth, $decodedHolder["date_of_birth"]);
        $this->assertSame($holder->country_of_birth, $decodedHolder["country_of_birth"]);

        $this->assertSame($request->instruction->funds_transfer_type, $decoded["instruction"]["funds_transfer_type"]);
        $this->assertSame($request->instruction->purpose, $decoded["instruction"]["purpose"]);
        $this->assertSame($request->amount, $decoded["amount"]);
        $this->assertSame($request->currency, $decoded["currency"]);
        $this->assertSame($request->payment_type, $decoded["payment_type"]);
        $this->assertSame($request->processing_channel_id, $decoded["processing_channel_id"]);
        $this->assertSame($request->reference, $decoded["reference"]);
        $this->assertSame($request->metadata, $decoded["metadata"]);
        $this->assertSame($request->previous_payment_id, $decoded["previous_payment_id"]);
        $this->assertSame($request->segment->brand, $decoded["segment"]["brand"]);
        $this->assertSame($request->segment->business_category, $decoded["segment"]["business_category"]);
        $this->assertSame($request->segment->market, $decoded["segment"]["market"]);
        $this->assertSame($request->processing->processing_speed, $decoded["processing"]["processing_speed"]);
    }
}
