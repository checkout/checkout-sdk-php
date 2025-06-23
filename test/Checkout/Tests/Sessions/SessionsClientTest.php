<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Sessions\Channel\AppSession;
use Checkout\Sessions\SessionRequest;
use Checkout\Sessions\SessionsClient;
use Checkout\Sessions\ThreeDsMethodCompletionRequest;
use Checkout\Tests\UnitTestFixture;

class SessionsClientTest extends UnitTestFixture
{
    /**
     * @var SessionsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new SessionsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestSessionCreateSessionOkResponse()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->requestSession(new SessionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldGetSessionDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["response"]);

        $response = $this->client->getSessionDetails("id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldGetSessionDetailsSessionSecret()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["response"]);

        $response = $this->client->getSessionDetails("id", "sessionSecr3t");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdateSessionDetails()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["response"]);

        $response = $this->client->updateSession("id", new AppSession());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdateSessionDetailsSessionSecret()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["response"]);

        $response = $this->client->updateSession("id", new AppSession(), "sessionSecr3t");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldCompleteSession()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->completeSession("id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldCompleteSessionSessionSecret()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["response"]);

        $response = $this->client->completeSession("id", "sessionSecr3t");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdate3dsMethodCompletionIndicator()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["response"]);

        $response = $this->client->updateThreeDsMethodCompletionIndicator("id", new ThreeDsMethodCompletionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldUpdate3dsMethodCompletionIndicatorSessionSecret()
    {
        $this->apiClient
            ->method("put")
            ->willReturn(["response"]);

        $response = $this->client->updateThreeDsMethodCompletionIndicator(
            "id",
            new ThreeDsMethodCompletionRequest(),
            "sessionSecr3t"
        );
        $this->assertNotNull($response);
    }

}
