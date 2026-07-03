<?php

namespace Checkout\Tests\Issuing\Disputes;

use Checkout\Issuing\Disputes\Entities\Evidence;
use Checkout\Issuing\Disputes\Entities\IssuingDisputeFraudDetails;
use Checkout\Issuing\Disputes\Entities\IssuingDisputeFraudType;
use Checkout\Issuing\Disputes\Requests\AmendDisputeRequest;
use Checkout\Issuing\Disputes\Requests\CreateDisputeRequest;
use Checkout\Issuing\Disputes\Requests\EscalateDisputeRequest;
use Checkout\JsonSerializer;
use PHPUnit\Framework\TestCase;

class DisputeRequestsSerializationTest extends TestCase
{
    private function fraudDetails()
    {
        $fraudDetails = new IssuingDisputeFraudDetails();
        $fraudDetails->fraud_type = IssuingDisputeFraudType::$card_stolen;
        $fraudDetails->description = "Card reported stolen";
        return $fraudDetails;
    }

    public function testAmendDisputeRequestRoundTrip()
    {
        $evidence = new Evidence();
        $evidence->name = "evidence.pdf";
        $evidence->content = "base64content";
        $evidence->description = "Supporting document";

        $request = new AmendDisputeRequest();
        $request->reason = "4853";
        $request->amount = 1000;
        $request->evidence = [$evidence];
        $request->fraud_details = $this->fraudDetails();
        $request->reason_change_justification = "Updated reason after review";
        $request->action_response = "Additional context provided";

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("4853", $decoded['reason']);
        $this->assertSame(1000, $decoded['amount']);
        $this->assertSame("evidence.pdf", $decoded['evidence'][0]['name']);
        $this->assertSame("base64content", $decoded['evidence'][0]['content']);
        $this->assertSame("card_stolen", $decoded['fraud_details']['fraud_type']);
        $this->assertSame("Card reported stolen", $decoded['fraud_details']['description']);
        $this->assertSame("Updated reason after review", $decoded['reason_change_justification']);
        $this->assertSame("Additional context provided", $decoded['action_response']);
    }

    public function testCreateDisputeRequestSerializesFraudDetails()
    {
        $request = new CreateDisputeRequest();
        $request->transaction_id = "trx_00000000000000000000000000";
        $request->reason = "10.4";
        $request->fraud_details = $this->fraudDetails();

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("card_stolen", $decoded['fraud_details']['fraud_type']);
        $this->assertSame("Card reported stolen", $decoded['fraud_details']['description']);
    }

    public function testEscalateDisputeRequestSerializesFraudDetails()
    {
        $request = new EscalateDisputeRequest();
        $request->justification = "Escalating after new evidence";
        $request->fraud_details = $this->fraudDetails();

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("card_stolen", $decoded['fraud_details']['fraud_type']);
    }

    /**
     * @dataProvider fraudTypeProvider
     */
    public function testFraudTypeSerializesToExactSwaggerValue($property, $expected)
    {
        $fraudDetails = new IssuingDisputeFraudDetails();
        $fraudDetails->fraud_type = IssuingDisputeFraudType::${$property};

        $decoded = json_decode((new JsonSerializer())->serialize($fraudDetails), true);

        $this->assertSame($expected, $decoded['fraud_type']);
    }

    public function fraudTypeProvider()
    {
        return [
            ["card_lost", "card_lost"],
            ["card_stolen", "card_stolen"],
            ["card_never_received", "card_never_received"],
            ["fraudulent_account", "fraudulent_account"],
            ["counterfeit_card", "counterfeit_card"],
            ["account_takeover", "account_takeover"],
            ["card_not_present_fraud", "card_not_present_fraud"],
            ["merchant_misrepresentation", "merchant_misrepresentation"],
            ["cardholder_manipulation", "cardholder_manipulation"],
            ["incorrect_processing", "incorrect_processing"],
            ["other", "other"],
        ];
    }
}
