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
}
