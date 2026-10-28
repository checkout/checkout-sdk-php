<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountHolder;
use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\AccountHolderIdentificationType;
use Checkout\Common\AccountHolderType;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use Checkout\Payments\Destination\PaymentRequestCardDestination;
use Checkout\Payments\Destination\PaymentRequestIdDestination;
use Checkout\Payments\Destination\PaymentRequestNetworkTokenDestination;
use Checkout\Payments\Destination\PaymentRequestTokenDestination;
use Checkout\Payments\FundTransferType;
use Checkout\Payments\NetworkTokenType;
use Checkout\Payments\PaymentDestinationType;
use Checkout\Payments\PaymentPurpose;
use Checkout\Payments\ProductSubType;
use Checkout\Payments\Request\CardPayoutItem;
use Checkout\Payments\Request\CardPayoutProcessing;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\PaymentSegment;
use Checkout\Payments\Request\PayoutRequest;
use Checkout\Payments\Request\Source\PayoutRequestCurrencyAccountSource;
use Checkout\Payments\Sender\PaymentIndividualSender;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class CardPayoutDestinationSerializationTest extends TestCase
{
    /**
     * Spec-shaped network token card payout destination, built from the
     * 02_CardPayoutRequest_DestinationTypeNetworkToken examples.
     */
    const NETWORK_TOKEN_FIXTURE = <<<'JSON'
{
  "type": "network_token",
  "token": "4543474002249996",
  "expiry_month": 6,
  "expiry_year": 2025,
  "token_type": "vts",
  "cryptogram": "hv8mUFzPzRZoCAAAAAEQBDMAAAA=",
  "eci": "05",
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
}
JSON;

    /**
     * Spec-shaped card payout request with all 13 CardPayoutRequest properties, built from the
     * CardPayoutRequest property examples (the schema has no request-level example).
     */
    const CARD_PAYOUT_FIXTURE = <<<'JSON'
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
  "amount": 1000,
  "currency": "GBP",
  "instruction": {
    "purpose": "family_support",
    "funds_transfer_type": "FD"
  },
  "items": [
    { "type": "digital", "sub_type": "stablecoin" }
  ],
  "processing_channel_id": "pc_q4dbxom5jbgudnjzjpz7j2z6uq",
  "sender": {
    "type": "individual",
    "reference": "8285282045818",
    "first_name": "John",
    "last_name": "Jones",
    "address": {
      "address_line1": "123 High St.",
      "city": "London",
      "country": "GB"
    },
    "reference_type": "other",
    "source_of_funds": "debit"
  },
  "reference": "ORD-5023-4E89",
  "previous_payment_id": "pay_fun26akvvjjerahhctaq2uzhu4",
  "metadata": { "coupon_code": "NY2018", "partner_id": 123989 },
  "segment": {
    "brand": "Acme Corporation",
    "business_category": "Inward Payments",
    "market": "UK"
  },
  "processing": {
    "affiliate_id": "AFF12345",
    "affiliate_url": "www.mycrypto.com",
    "purchase_country": "GB"
  }
}
JSON;

    const CARD_PAYOUT_KEYS = array(
        "source", "destination", "amount", "currency", "instruction", "items", "processing_channel_id",
        "sender", "reference", "previous_payment_id", "metadata", "segment", "processing"
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

    private function narrowAccountHolder()
    {
        $accountHolder = new AccountHolder();
        $accountHolder->type = AccountHolderType::$individual;
        $accountHolder->first_name = "John";
        $accountHolder->middle_name = "Paul";
        $accountHolder->last_name = "Smith";
        return $accountHolder;
    }

    private function networkTokenDestination()
    {
        $destination = new PaymentRequestNetworkTokenDestination();
        $destination->token = "4543474002249996";
        $destination->expiry_month = 6;
        $destination->expiry_year = 2025;
        $destination->token_type = NetworkTokenType::$vts;
        $destination->cryptogram = "hv8mUFzPzRZoCAAAAAEQBDMAAAA=";
        $destination->eci = "05";
        $destination->account_holder = $this->richAccountHolder();
        return $destination;
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

    private function fullCardPayoutRequest()
    {
        $source = new PayoutRequestCurrencyAccountSource();
        $source->id = "ca_y3oqhf46pyzuxjbcn2giaqnb44";

        $instruction = new PaymentInstruction();
        $instruction->purpose = PaymentPurpose::$family_support;
        $instruction->funds_transfer_type = FundTransferType::$FD;

        $item = new CardPayoutItem();
        $item->type = "digital";
        $item->sub_type = ProductSubType::$stablecoin;

        $senderAddress = new Address();
        $senderAddress->address_line1 = "123 High St.";
        $senderAddress->city = "London";
        $senderAddress->country = Country::$GB;

        $sender = new PaymentIndividualSender();
        $sender->reference = "8285282045818";
        $sender->first_name = "John";
        $sender->last_name = "Jones";
        $sender->address = $senderAddress;
        $sender->reference_type = "other";
        $sender->source_of_funds = "debit";

        $segment = new PaymentSegment();
        $segment->brand = "Acme Corporation";
        $segment->business_category = "Inward Payments";
        $segment->market = "UK";

        $processing = new CardPayoutProcessing();
        $processing->affiliate_id = "AFF12345";
        $processing->affiliate_url = "www.mycrypto.com";
        $processing->purchase_country = Country::$GB;

        $request = new PayoutRequest();
        $request->source = $source;
        $request->destination = $this->cardDestination();
        $request->amount = 1000;
        $request->currency = Currency::$GBP;
        $request->instruction = $instruction;
        $request->items = array($item);
        $request->processing_channel_id = "pc_q4dbxom5jbgudnjzjpz7j2z6uq";
        $request->sender = $sender;
        $request->reference = "ORD-5023-4E89";
        $request->previous_payment_id = "pay_fun26akvvjjerahhctaq2uzhu4";
        $request->metadata = array("coupon_code" => "NY2018", "partner_id" => 123989);
        $request->segment = $segment;
        $request->processing = $processing;
        return $request;
    }

    private function serializeToArray($object)
    {
        return json_decode((new JsonSerializer())->serialize($object), true);
    }

    public function testNetworkTokenDestinationSerializesLiteralWireKeys()
    {
        $decoded = $this->serializeToArray($this->networkTokenDestination());

        $expectedKeys = array(
            "type", "token", "expiry_month", "expiry_year", "token_type", "cryptogram", "eci", "account_holder"
        );
        $this->assertEqualsCanonicalizing($expectedKeys, array_keys($decoded));
        $this->assertSame("network_token", $decoded["type"]);
        foreach (array_keys($decoded) as $key) {
            $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $key);
        }
    }

    public function testNetworkTokenDestinationMatchesSpecFixture()
    {
        $expected = json_decode(self::NETWORK_TOKEN_FIXTURE, true);

        $decoded = $this->serializeToArray($this->networkTokenDestination());

        $this->assertEquals($expected, $decoded);
        $this->assertSame(6, $decoded["expiry_month"]);
        $this->assertSame(2025, $decoded["expiry_year"]);
    }

    public function testNetworkTokenDestinationOmitsUnsetOptionalFields()
    {
        $destination = $this->networkTokenDestination();
        $destination->cryptogram = null;
        $destination->eci = null;

        $decoded = $this->serializeToArray($destination);

        $this->assertEqualsCanonicalizing(
            array("type", "token", "expiry_month", "expiry_year", "token_type", "account_holder"),
            array_keys($decoded)
        );
    }

    public function testNetworkTokenTypeEnumValuesSerializeAsLiterals()
    {
        $values = (new ReflectionClass(NetworkTokenType::class))->getStaticProperties();
        $this->assertSame(
            array("vts" => "vts", "mdes" => "mdes", "applepay" => "applepay", "googlepay" => "googlepay"),
            $values
        );

        foreach ($values as $value) {
            $destination = $this->networkTokenDestination();
            $destination->token_type = $value;
            $this->assertSame($value, $this->serializeToArray($destination)["token_type"]);
        }
    }

    public function testPaymentDestinationTypeHasNetworkToken()
    {
        $this->assertSame("network_token", PaymentDestinationType::$network_token);
    }

    public function testPayoutRequestSerializesNetworkTokenDestination()
    {
        $request = new PayoutRequest();
        $request->destination = $this->networkTokenDestination();

        $destination = $this->serializeToArray($request)["destination"];

        $this->assertSame("network_token", $destination["type"]);
        $this->assertEqualsCanonicalizing(
            array(
                "type", "first_name", "middle_name", "last_name", "billing_address", "phone",
                "identification", "email", "date_of_birth", "country_of_birth"
            ),
            array_keys($destination["account_holder"])
        );
    }

    public function testNetworkTokenDestinationRoundTrip()
    {
        $destination = $this->networkTokenDestination();

        $decoded = (new JsonSerializer())->deserialize((new JsonSerializer())->serialize($destination));

        $this->assertSame($destination->type, $decoded["type"]);
        $this->assertSame($destination->token, $decoded["token"]);
        $this->assertSame($destination->expiry_month, $decoded["expiry_month"]);
        $this->assertSame($destination->expiry_year, $decoded["expiry_year"]);
        $this->assertSame($destination->token_type, $decoded["token_type"]);
        $this->assertSame($destination->cryptogram, $decoded["cryptogram"]);
        $this->assertSame($destination->eci, $decoded["eci"]);

        $holder = $destination->account_holder;
        $decodedHolder = $decoded["account_holder"];
        $this->assertSame($holder->type, $decodedHolder["type"]);
        $this->assertSame($holder->first_name, $decodedHolder["first_name"]);
        $this->assertSame($holder->middle_name, $decodedHolder["middle_name"]);
        $this->assertSame($holder->last_name, $decodedHolder["last_name"]);
        $this->assertSame(get_object_vars($holder->billing_address), $decodedHolder["billing_address"]);
        $this->assertSame(get_object_vars($holder->phone), $decodedHolder["phone"]);
        $this->assertSame(get_object_vars($holder->identification), $decodedHolder["identification"]);
        $this->assertSame("2030-01-01", $decodedHolder["identification"]["date_of_expiry"]);
        $this->assertSame($holder->email, $decodedHolder["email"]);
        $this->assertSame($holder->date_of_birth, $decodedHolder["date_of_birth"]);
        $this->assertSame($holder->country_of_birth, $decodedHolder["country_of_birth"]);
    }

    public function testCardDestinationSerializesAllProperties()
    {
        $decoded = $this->serializeToArray($this->cardDestination());

        $this->assertEqualsCanonicalizing(
            array("type", "number", "expiry_month", "expiry_year", "account_holder"),
            array_keys($decoded)
        );
        $this->assertSame("card", $decoded["type"]);
        $this->assertSame("4242424242424242", $decoded["number"]);
        $this->assertSame(6, $decoded["expiry_month"]);
        $this->assertSame(2030, $decoded["expiry_year"]);
        $this->assertEquals(
            json_decode(self::NETWORK_TOKEN_FIXTURE, true)["account_holder"],
            $decoded["account_holder"]
        );
    }

    public function testTokenDestinationSerializesAllProperties()
    {
        $destination = new PaymentRequestTokenDestination();
        $destination->token = "tok_ubfj2q76miwundwlk72vxt2i7q";
        $destination->account_holder = $this->narrowAccountHolder();

        $decoded = $this->serializeToArray($destination);

        $this->assertEqualsCanonicalizing(array("type", "token", "account_holder"), array_keys($decoded));
        $this->assertSame("token", $decoded["type"]);
        $this->assertSame("tok_ubfj2q76miwundwlk72vxt2i7q", $decoded["token"]);
        $this->assertEquals(
            array("type" => "individual", "first_name" => "John", "middle_name" => "Paul", "last_name" => "Smith"),
            $decoded["account_holder"]
        );
    }

    public function testNetworkTokenDestinationConstructorSetsType()
    {
        $this->assertSame("network_token", (new PaymentRequestNetworkTokenDestination())->type);
    }

    public function testCardPayoutRequestSerializesLiteralWireKeys()
    {
        $decoded = $this->serializeToArray($this->fullCardPayoutRequest());

        $this->assertEqualsCanonicalizing(self::CARD_PAYOUT_KEYS, array_keys($decoded));
        foreach (array_keys($decoded) as $key) {
            $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $key);
        }
        $this->assertSame(array("type", "id"), array_keys($decoded["source"]));
        $this->assertEqualsCanonicalizing(array("purpose", "funds_transfer_type"), array_keys($decoded["instruction"]));
        $this->assertSame(array("type", "sub_type"), array_keys($decoded["items"][0]));
        $this->assertEqualsCanonicalizing(
            array(
                "type", "reference", "first_name", "last_name", "address", "reference_type", "source_of_funds"
            ),
            array_keys($decoded["sender"])
        );
        $this->assertEqualsCanonicalizing(
            array("brand", "business_category", "market"),
            array_keys($decoded["segment"])
        );
        $this->assertEqualsCanonicalizing(
            array("affiliate_id", "affiliate_url", "purchase_country"),
            array_keys($decoded["processing"])
        );
    }

    public function testCardPayoutRequestMatchesSpecFixture()
    {
        $expected = json_decode(self::CARD_PAYOUT_FIXTURE, true);

        $decoded = $this->serializeToArray($this->fullCardPayoutRequest());

        $this->assertEquals($expected, $decoded);
        $this->assertSame(1000, $decoded["amount"]);
        $this->assertSame(123989, $decoded["metadata"]["partner_id"]);
    }

    public function testCardPayoutRequestRoundTrip()
    {
        $request = $this->fullCardPayoutRequest();
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($request));

        $this->assertSame(array("type" => $request->source->type, "id" => $request->source->id), $decoded["source"]);
        $this->assertSame($request->destination->type, $decoded["destination"]["type"]);
        $this->assertSame($request->destination->number, $decoded["destination"]["number"]);
        $this->assertSame($request->destination->expiry_month, $decoded["destination"]["expiry_month"]);
        $this->assertSame($request->destination->expiry_year, $decoded["destination"]["expiry_year"]);
        $this->assertEquals(
            json_decode(self::CARD_PAYOUT_FIXTURE, true)["destination"]["account_holder"],
            $decoded["destination"]["account_holder"]
        );
        $this->assertSame($request->amount, $decoded["amount"]);
        $this->assertSame($request->currency, $decoded["currency"]);
        $this->assertSame($request->instruction->purpose, $decoded["instruction"]["purpose"]);
        $this->assertSame(
            $request->instruction->funds_transfer_type,
            $decoded["instruction"]["funds_transfer_type"]
        );
        $this->assertSame(get_object_vars($request->items[0]), $decoded["items"][0]);
        $this->assertSame($request->processing_channel_id, $decoded["processing_channel_id"]);

        $sender = $request->sender;
        $decodedSender = $decoded["sender"];
        $this->assertSame($sender->type, $decodedSender["type"]);
        $this->assertSame($sender->reference, $decodedSender["reference"]);
        $this->assertSame($sender->first_name, $decodedSender["first_name"]);
        $this->assertSame($sender->last_name, $decodedSender["last_name"]);
        $this->assertSame($sender->reference_type, $decodedSender["reference_type"]);
        $this->assertSame($sender->source_of_funds, $decodedSender["source_of_funds"]);
        $this->assertSame(
            array_filter(get_object_vars($sender->address), function ($value) {
                return $value !== null;
            }),
            $decodedSender["address"]
        );

        $this->assertSame($request->reference, $decoded["reference"]);
        $this->assertSame($request->previous_payment_id, $decoded["previous_payment_id"]);
        $this->assertSame($request->metadata, $decoded["metadata"]);
        $this->assertSame(get_object_vars($request->segment), $decoded["segment"]);
        $this->assertSame($request->processing->affiliate_id, $decoded["processing"]["affiliate_id"]);
        $this->assertSame($request->processing->affiliate_url, $decoded["processing"]["affiliate_url"]);
        $this->assertSame($request->processing->purchase_country, $decoded["processing"]["purchase_country"]);
    }

    public function testPayoutRequestSerializesCardDestination()
    {
        $request = new PayoutRequest();
        $request->destination = $this->cardDestination();

        $decoded = $this->serializeToArray($request);

        $this->assertSame(array("destination"), array_keys($decoded));
        $this->assertEquals(json_decode(self::CARD_PAYOUT_FIXTURE, true)["destination"], $decoded["destination"]);
    }

    public function testPayoutRequestSerializesIdDestination()
    {
        $destination = new PaymentRequestIdDestination();
        $destination->id = "src_wmlfc3zyhqzehihu7giusaaawu";
        $destination->account_holder = $this->narrowAccountHolder();
        $request = new PayoutRequest();
        $request->destination = $destination;
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($request));

        $this->assertSame(array("destination"), array_keys($decoded));
        $this->assertEquals(
            array(
                "type" => "id",
                "id" => "src_wmlfc3zyhqzehihu7giusaaawu",
                "account_holder" => array(
                    "type" => "individual", "first_name" => "John", "middle_name" => "Paul", "last_name" => "Smith"
                )
            ),
            $decoded["destination"]
        );
    }

    public function testPayoutRequestSerializesTokenDestination()
    {
        $destination = new PaymentRequestTokenDestination();
        $destination->token = "tok_ubfj2q76miwundwlk72vxt2i7q";
        $destination->account_holder = $this->narrowAccountHolder();
        $request = new PayoutRequest();
        $request->destination = $destination;
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($request));

        $this->assertSame(array("destination"), array_keys($decoded));
        $this->assertEquals(
            array(
                "type" => "token",
                "token" => "tok_ubfj2q76miwundwlk72vxt2i7q",
                "account_holder" => array(
                    "type" => "individual", "first_name" => "John", "middle_name" => "Paul", "last_name" => "Smith"
                )
            ),
            $decoded["destination"]
        );
    }
}
