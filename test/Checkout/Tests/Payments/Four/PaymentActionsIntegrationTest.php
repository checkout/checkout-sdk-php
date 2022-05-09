<?php

namespace Checkout\Tests\Payments\Four;

use Checkout\CheckoutApiException;

class PaymentActionsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentActions()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $response = $this->retriable(
            function () use (&$paymentResponse) {
                return $this->fourApi->getPaymentsClient()->getPaymentActions($paymentResponse["id"]);
            },
            function ($response) {
                return sizeof($response["items"]) == 2;
            }
        );

        $actions = $response["items"];
        $this->assertNotNull($actions);
        $this->assertCount(2, $actions);
        foreach ($actions as $paymentAction) {
            $this->assertResponse(
                $paymentAction,
                "amount",
                "approved",
                "processed_on",
                "reference",
                "response_code",
                "response_summary",
                "type"
            );
        }
    }
}
