<?php

namespace Checkout\Tests\Forex;

use Checkout\CheckoutApiException;
use Checkout\Forex\ForexClient;
use Checkout\Forex\QuoteRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ForexClientTest extends UnitTestFixture
{
    private ForexClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new ForexClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestQuote(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestQuote(new QuoteRequest());
        $this->assertNotNull($response);
    }
}
