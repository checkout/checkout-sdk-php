<?php

namespace Checkout\Tests\Payments\Hosted;

use Checkout\Payments\Hosted\HostedPaymentsClient;
use Checkout\Payments\Hosted\HostedPaymentsSessionRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class HostedPaymentsClientTest extends UnitTestFixture
{
    /**
     * @var HostedPaymentsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new HostedPaymentsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     */
    public function shouldGetHostedPaymentsPageDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getHostedPaymentsPageDetails("id");
        $this->assertNotNull($response);
    }


    /**
     * @test
     */
    public function shouldCreateHostedPaymentsPageSession()
    {

        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->createHostedPaymentsPageSession(new HostedPaymentsSessionRequest());
        $this->assertNotNull($response);
    }

}
