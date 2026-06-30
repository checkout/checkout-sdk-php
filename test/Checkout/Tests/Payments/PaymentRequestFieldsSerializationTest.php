<?php

namespace Checkout\Tests\Payments;

use Checkout\JsonSerializer;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\Payments\Links\PaymentLinkRequest;
use Checkout\Payments\PaymentPlan;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
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
}
