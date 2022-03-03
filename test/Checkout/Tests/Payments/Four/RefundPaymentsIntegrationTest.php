<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Checkout\Payments\RefundRequest;

class RefundPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRefundCardPayment(): void
    {
        $paymentResponse = $this->makeCardPayment(true);

        $amount = $paymentResponse["amount"];
        $refundRequest = new RefundRequest();
        $refundRequest->reference = uniqid();
        $refundRequest->amount = $amount;

        $response = self::retriable(fn() => $this->fourApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest));

        $this->assertResponse($response, "reference", "action_id");

        $paymentDetails = self::retriable(fn() => $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]));

        $this->assertResponse($paymentDetails,
            "balances.total_authorized",
            "balances.total_captured",
            "balances.total_refunded");
        self::assertEquals($amount, $paymentDetails["balances"]["total_authorized"]);
        self::assertEquals($amount, $paymentDetails["balances"]["total_captured"]);
        self::assertEquals($amount, $paymentDetails["balances"]["total_refunded"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRefundCardPaymentIdempotent(): void
    {
        $paymentResponse = $this->makeCardPayment(true);

        $refundRequest = new RefundRequest();
        $refundRequest->reference = uniqid("shouldRefundCardPayment_Idempotent");
        $refundRequest->amount = 2;

        $idempotencyKey = $this->idempotencyKey();

        $response1 = self::retriable(fn() => $this->fourApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest, $idempotencyKey));

        $this->assertResponse($response1,
            "action_id",
            "reference");

        $refundRequest2 = new RefundRequest();
        $refundRequest2->reference = uniqid("shouldRefundCardPayment_Idempotent2");
        $refundRequest2->amount = 2;

        $response2 = self::retriable(fn() => $this->fourApi->getPaymentsClient()->refundPayment($paymentResponse["id"], $refundRequest2, $idempotencyKey));

        self::assertEquals($response1["action_id"], $response2["action_id"]);
    }
}
