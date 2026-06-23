<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Closure;

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
                return $this->checkoutApi->getPaymentsClient()->getPaymentDetails($paymentResponse["id"]);
            },
            self::paymentIsCaptured()
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
            "scheme_id",
            "source.id",
            "source.type",
            "source.fingerprint",
            "source.card_type",
            "customer.id",
            "customer.name"
        );

        // Mastercard Transaction Link Identifier - optional, only populated for Mastercard
        // transactions. The test card is not Mastercard, so the field is typically absent.
        // Reading it confirms the SDK exposes the field and that accessing it is safe even
        // when the response payload omits it.
        if (array_key_exists("processing", $payment)) {
            $schemeTransactionLinkId = $payment["processing"]["scheme_transaction_link_id"] ?? null;
            $this->assertTrue($schemeTransactionLinkId === null || is_string($schemeTransactionLinkId));
        }
    }

    /**
     * @return Closure
     */
    private function paymentIsCaptured()
    {
        return function ($response) {
            return array_key_exists("status", $response) && $response["status"] == "Captured";
        };
    }
}
