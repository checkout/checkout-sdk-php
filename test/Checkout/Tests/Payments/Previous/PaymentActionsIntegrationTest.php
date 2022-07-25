<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;
use Closure;

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
                return $this->previousApi->getPaymentsClient()->getPaymentActions($paymentResponse["id"]);
            },
            $this->thereAreTwoPaymentActions()
        );

        $actions = $response["items"];
        $this->assertNotNull($actions);
        $this->assertEquals(2, sizeof($actions));
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

    /**
     * @return Closure
     */
    private function thereAreTwoPaymentActions()
    {
        return function ($response) {
            return sizeof($response["items"]) == 2;
        };
    }
}
