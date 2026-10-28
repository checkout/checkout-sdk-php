<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountHolder;
use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\AccountHolderIdentificationType;
use Checkout\Common\AccountHolderType;
use Checkout\Common\Address;
use Checkout\Common\Phone;
use Checkout\JsonSerializer;
use Checkout\Payments\Destination\PaymentRequestCardDestination;
use Checkout\Payments\Destination\PaymentRequestNetworkTokenDestination;
use Checkout\Payments\Destination\PaymentRequestTokenDestination;
use Checkout\Payments\NetworkTokenType;
use Checkout\Payments\PaymentDestinationType;
use Checkout\Payments\Request\PayoutRequest;
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
        $destination = new PaymentRequestCardDestination();
        $destination->number = "4242424242424242";
        $destination->expiry_month = 6;
        $destination->expiry_year = 2030;
        $destination->account_holder = $this->richAccountHolder();

        $decoded = $this->serializeToArray($destination);

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
}
