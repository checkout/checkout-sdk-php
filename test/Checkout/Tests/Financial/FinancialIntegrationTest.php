<?php

namespace Checkout\Tests\Financial;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Financial\FinancialActionsQuery;
use Checkout\PlatformType;
use Checkout\Tests\Payments\AbstractPaymentsIntegrationTest;
use Closure;

class FinancialIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryFinancialActions()
    {
        $this->markTestSkipped("unstable");
        $payment = $this->makeCardPayment(true, 1040);

        $query = new FinancialActionsQuery();
        $query->payment_id = $payment["id"];
        $query->limit = 5;

        $queryFinancialActions = function () use (&$query) {
            return $this->checkoutApi->getFinancialClient()->query($query);
        };

        $response = $this->retriable($queryFinancialActions, $this->thereAreFinancialActions(), 4);

        $this->assertResponse(
            $response,
            "count",
            "data",
            "_links"
        );

        if (array_key_exists("data", $response)) {
            $actions = $response["data"];
            foreach ($actions as $action) {
                $this->assertResponse(
                    $action,
                    "payment_id",
                    "action_id",
                    "action_type",
                    "entity_id",
                    "currency_account_id",
                    "processed_on",
                    "requested_on"
                );
            }
        }
    }

    /**
     * @return Closure
     */
    private function thereAreFinancialActions()
    {
        return function ($response) {
            return array_key_exists("count", $response) && $response["count"] > 0;
        };
    }
}
