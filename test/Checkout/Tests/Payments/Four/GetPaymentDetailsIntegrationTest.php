<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;
use Closure;

class GetPaymentDetailsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentDetails(): void
    {
        $paymentResponse = $this->makeCardPayment(true);

        $payment = self::retriable(fn() => $this->fourApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]), $this->paymentIsCaptured());

        $this->assertResponse($payment,
            "id",
            "requested_on",
            "amount",
            "currency",
            "payment_type",
            "reference",
            "status",
            "approved",
            "scheme_id",
            "source.id",
            "source.type",
            "source.fingerprint",
            "source.card_type",
            "customer.id",
            "customer.name");
    }

    /**
     * @return Closure
     */
    private function paymentIsCaptured(): Closure
    {
        return fn($response): bool => array_key_exists("status", $response) && $response["status"] == "Captured";
    }
}
