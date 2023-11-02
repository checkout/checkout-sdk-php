<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Payments\CaptureRequest;
use Closure;

class CapturePaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFullCaptureCardPayment()
    {
        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid();
        $captureRequest->amount = 10;

        $response = $this->checkoutApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest);
        $this->assertResponse($response, "reference", "action_id");

        $paymentDetails = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            },
            $this->totalCapturedIs(10)
        );


        $this->assertResponse(
            $paymentDetails,
            "balances.total_authorized",
            "balances.total_captured",
            "balances.available_to_refund"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFullCaptureCardPaymentWithoutRequest()
    {
        $paymentResponse = $this->makeCardPayment();

        $response = $this->checkoutApi->getPaymentsClient()->capturePayment($paymentResponse["id"]);

        $this->assertResponse($response, "action_id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPartiallyCaptureCardPayment()
    {
        $paymentResponse = $this->makeCardPayment();

        $amount = $paymentResponse["amount"] / 2;
        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid();
        $captureRequest->amount = $amount;

        $response = $this->checkoutApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest);
        $this->assertResponse($response, "reference", "action_id");

        $paymentDetails = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            },
            $this->totalCapturedIs(5)
        );


        $this->assertResponse(
            $paymentDetails,
            "balances.total_authorized",
            "balances.total_captured",
            "balances.available_to_refund"
        );
        $this->assertEquals($amount, $paymentDetails["balances"]["total_captured"]);
        $this->assertEquals($amount, $paymentDetails["balances"]["available_to_refund"]);
    }

    /**
     * @throws CheckoutApiException
     */
    public function shouldCaptureCardPaymentIdempotently()
    {
        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid();

        $idempotencyKey = $this->idempotencyKey();

        $capture1 = $this->checkoutApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey);
        $this->assertNotNull($capture1);

        $capture2 = $this->checkoutApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey);
        $this->assertNotNull($capture2);

        $this->assertEquals($capture1["action_id"], $capture2["action_id"]);
    }

    /**
     * @param int $amount
     * @return Closure
     */
    private function totalCapturedIs($amount)
    {
        return function ($response) use (&$amount) {
            return array_key_exists("balances", $response) && $response["balances"]["total_captured"] == $amount;
        };
    }
}
