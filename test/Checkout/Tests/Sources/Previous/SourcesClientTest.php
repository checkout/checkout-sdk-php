<?php

namespace Checkout\Tests\Sources\Previous;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Sources\Previous\SepaSourceRequest;
use Checkout\Sources\Previous\SourcesClient;
use Checkout\Tests\UnitTestFixture;

class SourcesClientTest extends UnitTestFixture
{
    /**
     * @var SourcesClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$previous);
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
            ->willReturn(["foo"]);

        $response = $this->client->createSepaSource(new SepaSourceRequest());
        $this->assertNotNull($response);
    }

}
