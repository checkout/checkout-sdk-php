<?php

namespace Checkout\Tests\Forward;

use Checkout\CheckoutApiException;
use Checkout\Forward\ForwardClient;
use Checkout\Forward\Requests\ForwardRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ForwardClientTest extends UnitTestFixture
{
    /**
     * @var ForwardClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new ForwardClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldForwardAnApiRequest()
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");
        $response = $this->client->forwardAnApiRequest(new ForwardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetForwardRequest()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");
        $response = $this->client->getForwardRequest("forward_id");
        $this->assertNotNull($response);
    }
}
