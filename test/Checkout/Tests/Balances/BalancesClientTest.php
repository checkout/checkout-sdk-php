<?php

namespace Checkout\Tests\Balances;

use Checkout\Balances\BalancesClient;
use Checkout\Balances\BalancesQuery;
use Checkout\CheckoutApiException;
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
     */
    public function init()
    {
        $this->initMocks(PlatformType::$fourOAuth);
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
            ->willReturn("response");

        $response = $this->client->retrieveEntityBalances("entity_id", new BalancesQuery());

        $this->assertNotNull($response);
    }
}
