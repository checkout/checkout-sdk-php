<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountHolder;
use Checkout\Common\AccountHolderIdentification;
use Checkout\Common\AccountHolderIdentificationType;
use Checkout\Common\AccountHolderType;
use Checkout\Common\AccountType;
use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\Currency;
use Checkout\Common\Phone;
use Checkout\Common\ResidentialStatusType;
use Checkout\JsonSerializer;
use Checkout\Payments\Destination\PaymentBankAccountDestination;
use Checkout\Payments\InstructionRemittance;
use Checkout\Payments\Request\InstructionScheme;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\PaymentSegment;
use Checkout\Payments\Request\PayoutBillingDescriptor;
use Checkout\Payments\Request\PayoutRequest;
use Checkout\Payments\Request\Source\PayoutRequestEntitySource;
use Checkout\Payments\Sender\PaymentIndividualSender;
use PHPUnit\Framework\TestCase;

class BankPayoutSerializationTest extends TestCase
{
    /**
     * Built from the PaymentBankAccountDestination examples.
     */
    const BANK_ACCOUNT_FIXTURE = <<<'JSON'
{
  "type": "bank_account",
  "account_type": "savings",
  "account_number": "13654567455",
  "bank_code": "123-456",
  "branch_code": "6443",
  "bban": "3704 0044 0532 0130 00",
  "iban": "HU93116000060000000012345676",
  "swift_bic": "37040044",
  "country": "GB"
}
JSON;

    /**
     * Spec-shaped bank payout request with all 10 BankPayoutRequest properties, built from the
     * BankPayoutRequest property examples (the schema has no request-level example). The date of birth
     * values use the YYYY-MM-DD format that the descriptions require.
     */
    const BANK_PAYOUT_FIXTURE = <<<'JSON'
{
  "source": {
    "type": "entity",
    "id": "ent_w4jelhppmfiufdnatam37wrfc4"
  },
  "destination": {
    "type": "bank_account",
    "account_type": "savings",
    "account_number": "13654567455",
    "bank_code": "123-456",
    "branch_code": "6443",
    "bban": "3704 0044 0532 0130 00",
    "iban": "HU93116000060000000012345676",
    "swift_bic": "37040044",
    "country": "GB",
    "account_holder": {
      "type": "individual",
      "first_name": "John",
      "last_name": "Smith",
      "tax_id": "123456",
      "date_of_birth": "1986-01-01",
      "country_of_birth": "GB",
      "residential_status": "resident",
      "billing_address": {
        "address_line1": "123 High St.",
        "address_line2": "Flat 456",
        "city": "London",
        "zip": "SW1A 1AA",
        "country": "GB"
      },
      "phone": { "country_code": "+1", "number": "415 555 2671" }
    }
  },
  "amount": 6540,
  "currency": "USD",
  "reference": "Withdrawal",
  "billing_descriptor": { "reference": "Payout INV1" },
  "sender": {
    "type": "individual",
    "reference": "8285282045818",
    "first_name": "John",
    "last_name": "Jones",
    "dob": "1985-05-15",
    "address": {
      "address_line1": "123 High St.",
      "address_line2": "Flat 456",
      "city": "London",
      "zip": "SW1A 1AA",
      "country": "GB"
    },
    "identification": {
      "type": "driving_licence",
      "number": "1234",
      "issuing_country": "GT"
    }
  },
  "instruction": {
    "scheme": "local",
    "remittance": { "reference": "INV-2026-0001" }
  },
  "processing_channel_id": "pc_q4dbxom5jbgudnjzjpz7j2z6uq",
  "segment": {
    "brand": "Acme Corporation",
    "business_category": "Inward Payments",
    "market": "UK"
  }
}
JSON;

    const BANK_PAYOUT_KEYS = array(
        "source", "destination", "amount", "currency", "reference", "billing_descriptor", "sender",
        "instruction", "processing_channel_id", "segment"
    );

    private function bankAccountDestination()
    {
        $destination = new PaymentBankAccountDestination();
        $destination->account_type = AccountType::$savings;
        $destination->account_number = "13654567455";
        $destination->bank_code = "123-456";
        $destination->branch_code = "6443";
        $destination->bban = "3704 0044 0532 0130 00";
        $destination->iban = "HU93116000060000000012345676";
        $destination->swift_bic = "37040044";
        $destination->country = Country::$GB;
        return $destination;
    }

    private function specAddress()
    {
        $address = new Address();
        $address->address_line1 = "123 High St.";
        $address->address_line2 = "Flat 456";
        $address->city = "London";
        $address->zip = "SW1A 1AA";
        $address->country = Country::$GB;
        return $address;
    }

    private function fullBankPayoutRequest()
    {
        $source = new PayoutRequestEntitySource();
        $source->id = "ent_w4jelhppmfiufdnatam37wrfc4";

        $phone = new Phone();
        $phone->country_code = "+1";
        $phone->number = "415 555 2671";

        $accountHolder = new AccountHolder();
        $accountHolder->type = AccountHolderType::$individual;
        $accountHolder->first_name = "John";
        $accountHolder->last_name = "Smith";
        $accountHolder->tax_id = "123456";
        $accountHolder->date_of_birth = "1986-01-01";
        $accountHolder->country_of_birth = Country::$GB;
        $accountHolder->residential_status = ResidentialStatusType::$resident;
        $accountHolder->billing_address = $this->specAddress();
        $accountHolder->phone = $phone;

        $destination = $this->bankAccountDestination();
        $destination->account_holder = $accountHolder;

        $billingDescriptor = new PayoutBillingDescriptor();
        $billingDescriptor->reference = "Payout INV1";

        $identification = new AccountHolderIdentification();
        $identification->type = AccountHolderIdentificationType::$driving_licence;
        $identification->number = "1234";
        $identification->issuing_country = Country::$GT;

        $sender = new PaymentIndividualSender();
        $sender->reference = "8285282045818";
        $sender->first_name = "John";
        $sender->last_name = "Jones";
        $sender->dob = "1985-05-15";
        $sender->address = $this->specAddress();
        $sender->identification = $identification;

        $remittance = new InstructionRemittance();
        $remittance->reference = "INV-2026-0001";
        $instruction = new PaymentInstruction();
        $instruction->scheme = InstructionScheme::$local;
        $instruction->remittance = $remittance;

        $segment = new PaymentSegment();
        $segment->brand = "Acme Corporation";
        $segment->business_category = "Inward Payments";
        $segment->market = "UK";

        $request = new PayoutRequest();
        $request->source = $source;
        $request->destination = $destination;
        $request->amount = 6540;
        $request->currency = Currency::$USD;
        $request->reference = "Withdrawal";
        $request->billing_descriptor = $billingDescriptor;
        $request->sender = $sender;
        $request->instruction = $instruction;
        $request->processing_channel_id = "pc_q4dbxom5jbgudnjzjpz7j2z6uq";
        $request->segment = $segment;
        return $request;
    }

    public function testBankAccountDestinationMatchesSpecFixture()
    {
        $serializer = new JsonSerializer();

        $decoded = json_decode($serializer->serialize($this->bankAccountDestination()), true);

        $this->assertEquals(json_decode(self::BANK_ACCOUNT_FIXTURE, true), $decoded);
        $this->assertSame("3704 0044 0532 0130 00", $decoded["bban"]);
    }

    public function testBankAccountDestinationRoundTrip()
    {
        $destination = $this->bankAccountDestination();
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($destination));

        foreach (get_object_vars($destination) as $property => $value) {
            if ($value !== null) {
                $this->assertSame($value, $decoded[$property], $property);
            }
        }
        $this->assertSame("bank_account", $decoded["type"]);
    }

    public function testBankPayoutRequestSerializesBillingDescriptor()
    {
        $billingDescriptor = new PayoutBillingDescriptor();
        $billingDescriptor->reference = "Payout INV1";
        $request = new PayoutRequest();
        $request->destination = $this->bankAccountDestination();
        $request->billing_descriptor = $billingDescriptor;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame(array("reference" => "Payout INV1"), $decoded["billing_descriptor"]);
        $this->assertSame("bank_account", $decoded["destination"]["type"]);
    }

    public function testBankPayoutRequestSerializesInstructionSchemeAndRemittance()
    {
        $remittance = new InstructionRemittance();
        $remittance->reference = "INV-2026-0001";
        $instruction = new PaymentInstruction();
        $instruction->scheme = InstructionScheme::$local;
        $instruction->remittance = $remittance;
        $request = new PayoutRequest();
        $request->instruction = $instruction;

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame(
            array("scheme" => "local", "remittance" => array("reference" => "INV-2026-0001")),
            $decoded["instruction"]
        );
    }

    public function testBankPayoutRequestSerializesLiteralWireKeys()
    {
        $decoded = json_decode((new JsonSerializer())->serialize($this->fullBankPayoutRequest()), true);

        $this->assertEqualsCanonicalizing(self::BANK_PAYOUT_KEYS, array_keys($decoded));
        foreach (array_keys($decoded) as $key) {
            $this->assertMatchesRegularExpression('/^[a-z0-9_]+$/', $key);
        }
        $this->assertSame(array("type", "id"), array_keys($decoded["source"]));
        $this->assertEqualsCanonicalizing(
            array(
                "type", "first_name", "last_name", "tax_id", "date_of_birth", "country_of_birth",
                "residential_status", "billing_address", "phone"
            ),
            array_keys($decoded["destination"]["account_holder"])
        );
        $this->assertEqualsCanonicalizing(
            array("type", "reference", "first_name", "last_name", "dob", "address", "identification"),
            array_keys($decoded["sender"])
        );
        $this->assertEqualsCanonicalizing(array("scheme", "remittance"), array_keys($decoded["instruction"]));
    }

    public function testBankPayoutRequestMatchesSpecFixture()
    {
        $expected = json_decode(self::BANK_PAYOUT_FIXTURE, true);

        $decoded = json_decode((new JsonSerializer())->serialize($this->fullBankPayoutRequest()), true);

        $this->assertEquals($expected, $decoded);
        $this->assertSame(6540, $decoded["amount"]);
        $this->assertSame("entity", $decoded["source"]["type"]);
    }

    public function testBankPayoutRequestRoundTrip()
    {
        $request = $this->fullBankPayoutRequest();
        $serializer = new JsonSerializer();

        $decoded = $serializer->deserialize($serializer->serialize($request));

        $this->assertSame(array("type" => $request->source->type, "id" => $request->source->id), $decoded["source"]);
        foreach (get_object_vars($request->destination) as $property => $value) {
            if (is_string($value)) {
                $this->assertSame($value, $decoded["destination"][$property], $property);
            }
        }
        $holder = $request->destination->account_holder;
        $decodedHolder = $decoded["destination"]["account_holder"];
        foreach (get_object_vars($holder) as $property => $value) {
            if (is_string($value)) {
                $this->assertSame($value, $decodedHolder[$property], $property);
            }
        }
        $this->assertSame(get_object_vars($holder->phone), $decodedHolder["phone"]);
        $this->assertSame($request->amount, $decoded["amount"]);
        $this->assertSame($request->currency, $decoded["currency"]);
        $this->assertSame($request->reference, $decoded["reference"]);
        $this->assertSame(get_object_vars($request->billing_descriptor), $decoded["billing_descriptor"]);

        $sender = $request->sender;
        $decodedSender = $decoded["sender"];
        foreach (get_object_vars($sender) as $property => $value) {
            if (is_string($value)) {
                $this->assertSame($value, $decodedSender[$property], $property);
            }
        }
        $this->assertSame(
            array_filter(get_object_vars($sender->identification), function ($value) {
                return $value !== null;
            }),
            $decodedSender["identification"]
        );

        $this->assertSame($request->instruction->scheme, $decoded["instruction"]["scheme"]);
        $this->assertSame(
            $request->instruction->remittance->reference,
            $decoded["instruction"]["remittance"]["reference"]
        );
        $this->assertSame($request->processing_channel_id, $decoded["processing_channel_id"]);
        $this->assertSame(get_object_vars($request->segment), $decoded["segment"]);
        $this->assertEquals(json_decode(self::BANK_PAYOUT_FIXTURE, true), $decoded);
    }
}
