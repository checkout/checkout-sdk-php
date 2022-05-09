<?php

namespace Checkout\Tests\Forex;

use Checkout\Forex\ForexClient;
use Checkout\Forex\QuoteRequest;
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
        $this->initMocks(PlatformType::$default);
        $this->client = new ForexClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     */
    public function shouldRequestQuote()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestQuote(new QuoteRequest());
        $this->assertNotNull($response);
    }
}
