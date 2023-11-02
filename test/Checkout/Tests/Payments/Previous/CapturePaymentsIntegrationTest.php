<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;
use Checkout\Payments\Previous\CaptureRequest;

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
        $captureRequest->reference = uniqid("shouldFullCaptureCardPayment");

        $response = $this->retriable(
            function () use (&$paymentResponse, &$captureRequest) {
                return $this->previousApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest);
            }
        );

        $this->assertResponse($response, "reference", "action_id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFullCaptureCardPaymentWithoutRequest()
    {

        $paymentResponse = $this->makeCardPayment();

        $response = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->previousApi->getPaymentsClient()->capturePayment($paymentResponse["id"]);
            }
        );

        $this->assertResponse($response, "action_id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPartiallyCaptureCardPayment()
    {

        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid("shouldPartiallyCaptureCardPayment");
        $captureRequest->amount = 5;

        $response = $this->retriable(
            function () use (&$paymentResponse, &$captureRequest) {
                return $this->previousApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest);
            }
        );

        $this->assertResponse($response, "reference", "action_id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCaptureCardPaymentIdempotent()
    {
        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid("shouldCaptureCardPaymentIdempotent");

        $idempotencyKey = $this->idempotencyKey();

        $capture1 = $this->retriable(
            function () use (&$paymentResponse, &$captureRequest, &$idempotencyKey) {
                return $this->previousApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey);
            }
        );

        $this->assertNotNull($capture1);

        $capture2 = $this->retriable(
            function () use (&$paymentResponse, &$captureRequest, &$idempotencyKey) {
                return $this->previousApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey);
            }
        );

        $this->assertNotNull($capture2);

        $this->assertEquals($capture1["action_id"], $capture2["action_id"]);
    }
}
