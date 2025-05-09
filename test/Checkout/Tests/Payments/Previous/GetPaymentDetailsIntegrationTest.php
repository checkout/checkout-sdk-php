<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;

class GetPaymentDetailsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentDetails()
    {
        $this->markTestSkipped("unavailable");
        $paymentResponse = $this->makeCardPayment(true);

        $payment = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->previousApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            }
        );

        $this->assertResponse(
            $payment,
            "id",
            "requested_on",
            "amount",
            "currency",
            "payment_type",
            "reference",
            "status",
            "approved",
            //"eci",
            "scheme_id",
            "source.id",
            "source.type",
            "source.fingerprint",
            //"source.card_type",
            "customer.id",
            "customer.name"
        );
    }
}
