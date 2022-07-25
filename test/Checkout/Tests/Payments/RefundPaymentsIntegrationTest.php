<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Payments\RefundRequest;

class RefundPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRefundCardPayment()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $amount = $paymentResponse["amount"];
        $refundRequest = new RefundRequest();
        $refundRequest->reference = uniqid();
        $refundRequest->amount = $amount;

        $response = $this->retriable(
            function () use (&$paymentResponse, &$refundRequest) {
                return $this->checkoutApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest);
            }
        );

        $this->assertResponse($response, "reference", "action_id");

        $paymentDetails = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            }
        );

        $this->assertResponse(
            $paymentDetails,
            "balances.total_authorized",
            "balances.total_captured",
            "balances.total_refunded"
        );
        $this->assertEquals($amount, $paymentDetails["balances"]["total_authorized"]);
        $this->assertEquals($amount, $paymentDetails["balances"]["total_captured"]);
        $this->assertEquals($amount, $paymentDetails["balances"]["total_refunded"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRefundCardPaymentIdempotent()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $refundRequest = new RefundRequest();
        $refundRequest->reference = uniqid("shouldRefundCardPayment_Idempotent");
        $refundRequest->amount = 2;

        $idempotencyKey = $this->idempotencyKey();

        $response1 = $this->retriable(
            function () use (&$paymentResponse, &$refundRequest, &$idempotencyKey) {
                return $this->checkoutApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest, $idempotencyKey);
            }
        );

        $this->assertResponse(
            $response1,
            "action_id",
            "reference"
        );

        $refundRequest2 = new RefundRequest();
        $refundRequest2->reference = uniqid("shouldRefundCardPayment_Idempotent2");
        $refundRequest2->amount = 2;

        $response2 = $this->retriable(
            function () use (&$paymentResponse, &$refundRequest2, &$idempotencyKey) {
                return $this->checkoutApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest2, $idempotencyKey);
            }
        );

        $this->assertEquals($response1["action_id"], $response2["action_id"]);
    }
}
