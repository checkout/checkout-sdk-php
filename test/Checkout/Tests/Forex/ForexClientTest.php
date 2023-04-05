<?php

namespace Checkout\Tests\Forex;

use Checkout\CheckoutApiException;
use Checkout\Forex\ForexClient;
use Checkout\Forex\QuoteRequest;
use Checkout\Forex\RatesQueryFilter;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ForexClientTest extends UnitTestFixture
{
    /**
     * @var ForexClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$previous);
        $this->client = new ForexClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestQuote()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestQuote(new QuoteRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetRates()
    {

        $this->apiClient
            ->method("query")
            ->willReturn("foo");

        $response = $this->client->getRates(new RatesQueryFilter());
        $this->assertNotNull($response);
    }
}
