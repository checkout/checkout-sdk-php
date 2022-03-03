<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\PlatformType;
use Checkout\Sessions\Channel\AppSession;
use Checkout\Sessions\SessionRequest;
use Checkout\Sessions\SessionsClient;
use Checkout\Sessions\ThreeDsMethodCompletionRequest;
use Checkout\Tests\UnitTestFixture;

class SessionsClientTest extends UnitTestFixture
{
    private SessionsClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$fourOAuth);
        $this->client = new SessionsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestSessionCreateSessionOkResponse(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->requestSession(new SessionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldGetSessionDetails(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getSessionDetails("id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldGetSessionDetailsSessionSecret(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("response");

        $response = $this->client->getSessionDetails("id", "sessionSecr3t");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateSessionDetails(): void
    {
        $this->apiClient
            ->method("put")
            ->willReturn("response");

        $response = $this->client->updateSession("id", new AppSession());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateSessionDetailsSessionSecret(): void
    {
        $this->apiClient
            ->method("put")
            ->willReturn("response");

        $response = $this->client->updateSession("id", new AppSession(), "sessionSecr3t");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldCompleteSession(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->completeSession("id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldCompleteSessionSessionSecret(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("response");

        $response = $this->client->completeSession("id", "sessionSecr3t");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdate3dsMethodCompletionIndicator(): void
    {
        $this->apiClient
            ->method("put")
            ->willReturn("response");

        $response = $this->client->updateThreeDsMethodCompletionIndicator("id", new ThreeDsMethodCompletionRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdate3dsMethodCompletionIndicatorSessionSecret(): void
    {
        $this->apiClient
            ->method("put")
            ->willReturn("response");

        $response = $this->client->updateThreeDsMethodCompletionIndicator("id", new ThreeDsMethodCompletionRequest(), "sessionSecr3t");
        $this->assertNotNull($response);
    }

}
