<?php

namespace Checkout\Tests\Payments\Hosted;

use Checkout\CheckoutApiException;
use Checkout\Payments\Hosted\HostedPaymentsClient;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;


class HostedPaymentsClientTest extends UnitTestFixture
{
    private HostedPaymentsClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new HostedPaymentsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetHostedPaymentsPageDetails(): void
    {

        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getHostedPaymentsPageDetails("id");
        $this->assertNotNull($response);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateHostedPaymentsPageSession(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->createHostedPaymentsPageSession(new HostedPaymentsSessionRequest());
        $this->assertNotNull($response);
    }

}
