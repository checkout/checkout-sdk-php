<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Payments\CaptureRequest;

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
        $captureRequest->reference = uniqid("shouldFullCaptureCardPayment");

        $response = self::retriable(fn() => $this->defaultApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest));

        $this->assertResponse($response, "reference", "action_id");

    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPartiallyCaptureCardPayment(): void
    {

        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid("shouldPartiallyCaptureCardPayment");
        $captureRequest->amount = 5;

        $response = self::retriable(fn() => $this->defaultApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest));

        $this->assertResponse($response, "reference", "action_id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCaptureCardPaymentIdempotent(): void
    {
        $paymentResponse = $this->makeCardPayment();

        $captureRequest = new CaptureRequest();
        $captureRequest->reference = uniqid("shouldCaptureCardPaymentIdempotent");

        $idempotencyKey = $this->idempotencyKey();

        $capture1 = self::retriable(fn() => $this->defaultApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey));

        self::assertNotNull($capture1);

        $capture2 = self::retriable(fn() => $this->defaultApi->getPaymentsClient()->capturePayment($paymentResponse["id"], $captureRequest, $idempotencyKey));

        self::assertNotNull($capture2);

        self::assertEquals($capture1["action_id"], $capture2["action_id"]);
    }
}
