<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountType;
use Checkout\Common\Country;
use Checkout\JsonSerializer;
use Checkout\Payments\Destination\PaymentBankAccountDestination;
use Checkout\Payments\Request\PayoutBillingDescriptor;
use Checkout\Payments\Request\PayoutRequest;
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
}
