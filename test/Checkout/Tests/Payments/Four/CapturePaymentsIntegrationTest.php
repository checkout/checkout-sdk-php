<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Checkout\Payments\Four\CaptureRequest;
use Closure;

class CapturePaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFullCaptureCardPayment(): void
    {
        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid();
        $captureRequest->amount = 10;

        $response = $this->fourApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest);
        $this->assertResponse($response, "reference", "action_id");

        $paymentDetails = self::retriable(fn() => $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]), $this->totalCapturedIs(10));

        $this->assertResponse($paymentDetails,
            "balances.total_authorized",
            "balances.total_captured",
            "balances.available_to_refund");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPartiallyCaptureCardPayment(): void
    {
        $paymentResponse = $this->makeCardPayment();

        $amount = $paymentResponse["amount"] / 2;
        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid();
        $captureRequest->amount = $amount;

        $response = $this->fourApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest);
        $this->assertResponse($response, "reference", "action_id");

        $paymentDetails = self::retriable(fn() => $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]), $this->totalCapturedIs(5));

        $this->assertResponse($paymentDetails,
            "balances.total_authorized",
            "balances.total_captured",
            "balances.available_to_refund");
        self::assertEquals($amount, $paymentDetails["balances"]["total_captured"]);
        self::assertEquals($amount, $paymentDetails["balances"]["available_to_refund"]);
    }

    /**
     * @throws CheckoutApiException
     */
    public function shouldCaptureCardPaymentIdempotently(): void
    {
        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid();

        $idempotencyKey = $this->idempotencyKey();

        $capture1 = $this->fourApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey);
        self::assertNotNull($capture1);

        $capture2 = $this->fourApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey);
        self::assertNotNull($capture2);

        self::assertEquals($capture1["action_id"], $capture2["action_id"]);
    }

    /**
     * @param int $amount
     * @return Closure
     */
    private function totalCapturedIs(int $amount): Closure
    {
        return fn($response): bool => array_key_exists("balances", $response) && $response["balances"]["total_captured"] == $amount;
    }
}
