<?php

namespace Checkout\Tests\Balances;

use Checkout\Balances\BalancesClient;
use Checkout\Balances\BalancesQuery;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class BalancesClientTest extends UnitTestFixture
{
    /**
     * @var BalancesClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new BalancesClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveEntityBalances()
    {
        $this->apiClient
            ->method("query")
            ->willReturn(["response"]);

        $response = $this->client->retrieveEntityBalances("entity_id", new BalancesQuery());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveTopUpInstructions()
    {
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with(
                "entities/ent_w4jelhppmfiufdnatam37wrfc4/currency-accounts/ca_g5y7d6jo4e2urgforcbf2ey5jm/top-up-instructions",
                $this->anything()
            )
            ->willReturn(["response"]);

        $response = $this->client->retrieveTopUpInstructions(
            "ent_w4jelhppmfiufdnatam37wrfc4",
            "ca_g5y7d6jo4e2urgforcbf2ey5jm"
        );

        $this->assertNotNull($response);
    }
}
