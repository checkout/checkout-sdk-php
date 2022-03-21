<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;

class GetPaymentDetailsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentDetails()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $payment = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->defaultApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            });

        $this->assertResponse($payment,
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
            "customer.name");
    }
}
