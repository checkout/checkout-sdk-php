<?php

namespace Checkout\Tests\Payments\Previous;

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

        $refundRequest = new RefundRequest();
        $refundRequest->reference = uniqid();

        $response = $this->retriable(
            function () use (&$paymentResponse, &$refundRequest) {
                return $this->previousApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest);
            }
        );

        $this->assertResponse(
            $response,
            "action_id",
            "reference"
        );
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
                return $this->previousApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest, $idempotencyKey);
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
                return $this->previousApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest2, $idempotencyKey);
            }
        );

        $this->assertEquals($response1["action_id"], $response2["action_id"]);
    }

}
