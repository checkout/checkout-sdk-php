<?php

namespace Checkout\Tests\Payments;

use Checkout\Common\AccountHolder;
use Checkout\Common\BankDetails;
use Checkout\JsonSerializer;
use Checkout\Payments\Destination\PaymentBankAccountDestination;
use Checkout\Payments\Request\InstructionScheme;
use Checkout\Payments\Request\PaymentInstruction;
use Checkout\Payments\Request\PayoutRequest;
use Checkout\Payments\Request\Source\PayoutRequestCurrencyAccountSource;
use PHPUnit\Framework\TestCase;

/**
 * Deprecated payout members are retained for backwards compatibility until the next major version, so
 * they must keep serializing under their wire keys.
 */
class DeprecatedPayoutMembersSerializationTest extends TestCase
{
    private function serialize($object)
    {
        return json_decode((new JsonSerializer())->serialize($object), true);
    }

    public function testDeprecatedInstructionMembersStillSerialize()
    {
        $instruction = new PaymentInstruction();
        $instruction->charge_bearer = "OUR";
        $instruction->repair = true;
        $instruction->quote_id = "qte_mbabizu24mvu3mela5njyhpit4";
        $instruction->scheme = InstructionScheme::$swift;
        $request = new PayoutRequest();
        $request->instruction = $instruction;

        $decoded = $this->serialize($request)["instruction"];

        $this->assertSame(array(
            "charge_bearer" => "OUR",
            "repair" => true,
            "scheme" => "swift",
            "quote_id" => "qte_mbabizu24mvu3mela5njyhpit4",
        ), $decoded);
    }

    public function testDeprecatedSourceAmountStillSerializes()
    {
        $source = new PayoutRequestCurrencyAccountSource();
        $source->id = "ca_y3oqhf46pyzuxjbcn2giaqnb44";
        $source->amount = 1000;
        $request = new PayoutRequest();
        $request->source = $source;

        $decoded = $this->serialize($request)["source"];

        $this->assertSame(1000, $decoded["amount"]);
        $this->assertSame("ca_y3oqhf46pyzuxjbcn2giaqnb44", $decoded["id"]);
    }

    public function testDeprecatedBankAccountDestinationBankStillSerializes()
    {
        $bank = new BankDetails();
        $bank->name = "Lloyds TSB";
        $bank->branch = "Bournemouth";
        $destination = new PaymentBankAccountDestination();
        $destination->bank = $bank;
        $request = new PayoutRequest();
        $request->destination = $destination;

        $decoded = $this->serialize($request)["destination"];

        $this->assertSame(array("name" => "Lloyds TSB", "branch" => "Bournemouth"), $decoded["bank"]);
        $this->assertSame("bank_account", $decoded["type"]);
    }

    public function testDeprecatedAccountHolderGenderStillSerializes()
    {
        $accountHolder = new AccountHolder();
        $accountHolder->first_name = "John";
        $accountHolder->gender = "male";

        $decoded = $this->serialize($accountHolder);

        $this->assertSame(array("first_name" => "John", "gender" => "male"), $decoded);
    }
}
