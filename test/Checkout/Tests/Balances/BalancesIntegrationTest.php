<?php

namespace Checkout\Tests\Balances;

use Checkout\Balances\BalancesQuery;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class BalancesIntegrationTest extends SandboxTestFixture
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
    public function shouldRetrieveEntityBalances()
    {
        $balancesQuery = new BalancesQuery();
        $balancesQuery->query = "currency:" . Currency::$GBP;

        $balances = $this->checkoutApi->getBalancesClient()->retrieveEntityBalances("ent_kidtcgc3ge5unf4a5i6enhnr5m", $balancesQuery);
        $this->assertResponse($balances, "data", "_links");
        foreach ($balances["data"] as $balanceData) {
            $this->assertResponse(
                $balanceData,
                "descriptor",
                "holding_currency",
                "balances",
                "balances.available"
            );
        }
    }
}
