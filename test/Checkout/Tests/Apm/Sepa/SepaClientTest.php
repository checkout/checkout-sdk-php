<?php

namespace Checkout\Tests\Apm\Sepa;

use Checkout\Apm\Sepa\SepaClient;
use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class SepaClientTest extends UnitTestFixture
{
    private SepaClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new SepaClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function getMandate(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getMandate("mandate_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function cancelMandate(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->cancelMandate("mandate_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function getMandateViaPPro(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->getMandateViaPPro("mandate_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function cancelMandateViaPPro(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->cancelMandateViaPPro("mandate_id");
        $this->assertNotNull($response);
    }

}
