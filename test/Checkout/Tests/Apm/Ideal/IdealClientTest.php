<?php

namespace Checkout\Tests\Apm\Ideal;

use Checkout\Apm\Ideal\IdealClient;
use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class IdealClientTest extends UnitTestFixture
{
    private IdealClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new IdealClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetInfo(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getInfo();
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIssuers(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getIssuers();
        $this->assertNotNull($response);
    }
}
