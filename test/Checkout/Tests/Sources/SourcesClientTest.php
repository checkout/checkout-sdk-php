<?php

namespace Checkout\Tests\Sources;

use Checkout\PlatformType;
use Checkout\Sources\SepaSourceRequest;
use Checkout\Sources\SourcesClient;
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
        $this->initMocks(PlatformType::$default);
        $this->client = new SourcesClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
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
