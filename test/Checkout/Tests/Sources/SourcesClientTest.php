<?php

namespace Checkout\Tests\Sources;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Sources\SepaSourceRequest;
use Checkout\Sources\SourcesClient;
use Checkout\Tests\UnitTestFixture;

class SourcesClientTest extends UnitTestFixture
{
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new SourcesClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateSepaSource()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->createSepaSource(new SepaSourceRequest());
        $this->assertNotNull($response);
    }

}
