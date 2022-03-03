<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Checkout\Payments\VoidRequest;

class VoidPaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldVoidCardPayment(): void
    {
        $paymentResponse = $this->makeCardPayment();

        $voidRequest = new VoidRequest();
        $voidRequest->reference = uniqid("shouldVoidCardPayment");

        $response = self::retriable(fn() => $this->fourApi->getPaymentsClient()->voidPayment($paymentResponse["id"], $voidRequest));

        $this->assertResponse($response,
            "action_id",
            "reference");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldVoidCardPayment_Idempotent(): void
    {
        $paymentResponse = $this->makeCardPayment();

        $voidRequest = new VoidRequest();
        $voidRequest->reference = uniqid("shouldVoidCardPayment");

        $idempotencyKey = $this->idempotencyKey();

        $response1 = self::retriable(fn() => $this->fourApi->getPaymentsClient()->voidPayment($paymentResponse["id"], $voidRequest, $idempotencyKey));

        self::assertNotNull($response1);

        $response2 = self::retriable(fn() => $this->fourApi->getPaymentsClient()->voidPayment($paymentResponse["id"], $voidRequest, $idempotencyKey));

        self::assertNotNull($response2);

        self::assertEquals($response1["action_id"], $response2["action_id"]);
    }
}
