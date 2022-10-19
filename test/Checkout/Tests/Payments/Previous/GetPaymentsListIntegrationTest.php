<?php

namespace Checkout\Tests\Payments\Previous;

use Checkout\CheckoutApiException;
use Checkout\Payments\PaymentsQueryFilter;
use Closure;

class GetPaymentsListIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetPaymentsList()
    {
        $paymentResponse = $this->makeCardPayment();

        $query = new PaymentsQueryFilter();
        $query->limit = 100;
        $query->skip = 0;
        $query->reference = $paymentResponse["reference"];

        $response = $this->retriable(
            function () use (&$query) {
                return $this->previousApi->getPaymentsClient()->getPaymentsList($query);
            },
            $this->thereArePayments()
        );

        $this->assertResponse(
            $response,
            "http_metadata",
            "total_count",
            "data"
        );

        $this->assertEquals($paymentResponse["reference"], $response["data"][0]["reference"]);
    }

    /**
     * @return Closure
     */
    private function thereArePayments()
    {
        return function ($response) {
            return array_key_exists("total_count", $response) && $response["total_count"] != 0;
        };
    }
}
