<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Closure;

class PaymentActionsIntegrationTest extends AbstractPaymentsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentActions(): void
    {
        $paymentResponse = $this->makeCardPayment(true);

        $actions = self::retriable(fn() => $this->defaultApi->getPaymentsClient()->getPaymentActions($paymentResponse["id"]), $this->thereAreTwoPaymentActions());

        self::assertNotNull($actions);
        self::assertCount(2, $actions);
        foreach ($actions as $paymentAction) {
            $this->assertResponse($paymentAction,
                "amount",
                "approved",
                "processed_on",
                "reference",
                "response_code",
                "response_summary",
                "type");
        }
    }

    /**
     * @return Closure
     */
    private function thereAreTwoPaymentActions(): Closure
    {
        return fn($response) => sizeof($response) == 2;
    }
}
