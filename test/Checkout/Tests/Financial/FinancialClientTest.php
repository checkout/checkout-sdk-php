<?php

namespace Checkout\Tests\Financial;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Financial\FinancialActionsQuery;
use Checkout\Financial\FinancialClient;
use Checkout\PlatformType;
use Checkout\Reports\ReportsQuery;
use Checkout\Tests\UnitTestFixture;

class FinancialClientTest extends UnitTestFixture
{
    /**
     * @var FinancialClient
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
        $this->initMocks(PlatformType::$default);
        $this->client = new FinancialClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryFinancialActions()
    {
        $this->apiClient
            ->method("query")
            ->willReturn("foo");

        $response = $this->client->query(new FinancialActionsQuery());
        $this->assertNotNull($response);
    }
}
