<?php

namespace Checkout\Tests\Payments;

use Checkout\JsonSerializer;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\Payments\Links\PaymentLinkRequest;
use Checkout\Payments\NetworkTokenType;
use Checkout\Payments\PaymentInstruction;
use Checkout\Payments\PaymentPlan;
use Checkout\Payments\PaymentType;
use Checkout\Payments\Purpose;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\Source\RequestNetworkTokenSource;
use Checkout\Payments\Sessions\PaymentSessionCompleteRequest;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
use Checkout\Payments\Sessions\PaymentSessionSubmitRequest;
use PHPUnit\Framework\TestCase;

class PaymentRequestFieldsSerializationTest extends TestCase
{
    private function paymentPlan()
    {
        $plan = new PaymentPlan();
        $plan->amount_variability = 'Variable';
        $plan->days_between_payments = 28;
        $plan->total_number_of_payments = 5;
        $plan->current_payment_number = 3;
        return $plan;
    }

    public function testHostedPaymentsSessionRequestSerializesAuthorizationTypeAndPaymentPlan()
    {
        $request = new HostedPaymentsSessionRequest();
        $request->authorization_type = "Estimated";
        $request->payment_plan = $this->paymentPlan();

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("Estimated", $decoded['authorization_type']);
        $this->assertSame('Variable', $decoded['payment_plan']['amount_variability']);
        $this->assertSame(28, $decoded['payment_plan']['days_between_payments']);
        $this->assertSame(5, $decoded['payment_plan']['total_number_of_payments']);
    }

    public function testPaymentLinkRequestSerializesAuthorizationTypeAndPaymentPlan()
    {
        $request = new PaymentLinkRequest();
        $request->authorization_type = "Final";
        $request->payment_plan = $this->paymentPlan();

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("Final", $decoded['authorization_type']);
        $this->assertSame('Variable', $decoded['payment_plan']['amount_variability']);
        $this->assertSame(28, $decoded['payment_plan']['days_between_payments']);
    }

    public function testPaymentSessionsRequestSerializesAuthorizationTypeAndPaymentPlan()
    {
        $request = new PaymentSessionsRequest();
        $request->authorization_type = "Estimated";
        $request->payment_plan = $this->paymentPlan();

        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertSame("Estimated", $decoded['authorization_type']);
        $this->assertSame('Variable', $decoded['payment_plan']['amount_variability']);
        $this->assertSame(3, $decoded['payment_plan']['current_payment_number']);
    }

    public function testUnsetFieldsAreOmittedFromSerialization()
    {
        $request = new PaymentSessionsRequest();
        $decoded = json_decode((new JsonSerializer())->serialize($request), true);

        $this->assertArrayNotHasKey('authorization_type', $decoded);
        $this->assertArrayNotHasKey('payment_plan', $decoded);
    }

    public function testNetworkTokenSourceSerializesEveryTokenTypeLiteral()
    {
        foreach (array("vts", "mdes", "applepay", "googlepay") as $name) {
            $source = new RequestNetworkTokenSource();
            $source->token_type = NetworkTokenType::$$name;

            $decoded = json_decode((new JsonSerializer())->serialize($source), true);

            $this->assertSame($name, $decoded['token_type']);
            $this->assertSame('network_token', $decoded['type']);
        }
    }

    public function testPaymentTypeHasEverySpecValue()
    {
        $this->assertSame(
            array(
                "regular" => "Regular", "recurring" => "Recurring", "moto" => "MOTO",
                "installment" => "Installment", "pay_later" => "PayLater", "unscheduled" => "Unscheduled"
            ),
            (new \ReflectionClass(PaymentType::class))->getStaticProperties()
        );

        $request = new PaymentRequest();
        $request->payment_type = PaymentType::$pay_later;
        $decoded = json_decode((new JsonSerializer())->serialize($request), true);
        $this->assertSame("PayLater", $decoded['payment_type']);
    }

    public function testPaymentRequestSerializesInstructionPurpose()
    {
        $instruction = new PaymentInstruction();
        $instruction->purpose = Purpose::$family_support;
        $request = new PaymentRequest();
        $request->instruction = $instruction;
        $serializer = new JsonSerializer();

        $decoded = json_decode($serializer->serialize($request), true);

        $this->assertSame(array("purpose" => "family_support"), $decoded['instruction']);
        $roundTrip = $serializer->deserialize($serializer->serialize($instruction));
        $this->assertSame($instruction->purpose, $roundTrip['purpose']);
    }

    public function testPaymentSessionSubmitAndCompleteSerializeInstructionPurpose()
    {
        $instruction = new PaymentInstruction();
        $instruction->purpose = Purpose::$education;
        $submit = new PaymentSessionSubmitRequest();
        $submit->instruction = $instruction;
        $complete = new PaymentSessionCompleteRequest();
        $complete->instruction = $instruction;
        $serializer = new JsonSerializer();

        $expected = array("purpose" => "education");
        $this->assertSame($expected, json_decode($serializer->serialize($submit), true)['instruction']);
        $this->assertSame($expected, json_decode($serializer->serialize($complete), true)['instruction']);
    }
}
