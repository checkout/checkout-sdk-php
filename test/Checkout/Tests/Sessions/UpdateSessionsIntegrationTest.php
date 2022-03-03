<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Sessions\Channel\ThreeDsMethodCompletion;
use Checkout\Sessions\ThreeDsMethodCompletionRequest;

class UpdateSessionsIntegrationTest extends AbstractSessionsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateCardSessionUsingId_browserSession(): void
    {
        $responseHostedSession = $this->createHostedSession();

        $this->assertNotNull($responseHostedSession);

        $sessionId = $responseHostedSession["id"];

        $responseSessionDetails = $this->fourApi->getSessionsClient()->updateSession($sessionId, $this->getBrowserSession());
        $this->assertNotNull($responseSessionDetails);

    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateCardSessionUsingSessionSecret_browserSession(): void
    {
        $responseHostedSession = $this->createHostedSession();

        $this->assertNotNull($responseHostedSession);

        $sessionId = $responseHostedSession["id"];
        $sessionSecret = $responseHostedSession["session_secret"];

        $responseSessionDetailsWithSecret = $this->fourApi->getSessionsClient()->updateSession($sessionId, $this->getBrowserSession(), $sessionSecret);
        $this->assertNotNull($responseSessionDetailsWithSecret);

    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateCardSession_appSession(): void
    {
        $responseHostedSession = $this->createHostedSession();
        $this->assertNotNull($responseHostedSession);

        $sessionId = $responseHostedSession["id"];
        $responseSessionDetails = $this->fourApi->getSessionsClient()->updateSession($sessionId, $this->getAppSession());
        $this->assertNotNull($responseSessionDetails);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdate3dsMethodCompletionIndicator_sessionId(): void
    {
        $responseHostedSession = $this->createHostedSession();
        $this->assertNotNull($responseHostedSession);

        $threeDsMethodCompletionRequest = new ThreeDsMethodCompletionRequest();
        $threeDsMethodCompletionRequest->three_ds_method_completion = ThreeDsMethodCompletion::$y;

        $sessionId = $responseHostedSession["id"];

        $responseSessionDetails = $this->fourApi->getSessionsClient()->updateThreeDsMethodCompletionIndicator($sessionId, $threeDsMethodCompletionRequest);
        $this->assertNotNull($responseSessionDetails);

    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdate3dsMethodCompletionIndicator_sessionSecret(): void
    {
        $responseHostedSession = $this->createHostedSession();
        $this->assertNotNull($responseHostedSession);

        $threeDsMethodCompletionRequest = new ThreeDsMethodCompletionRequest();
        $threeDsMethodCompletionRequest->three_ds_method_completion = ThreeDsMethodCompletion::$y;

        $sessionId = $responseHostedSession["id"];
        $sessionSecret = $responseHostedSession["session_secret"];

        $responseSessionDetailsWithSecret = $this->fourApi->getSessionsClient()->updateThreeDsMethodCompletionIndicator($sessionId, $threeDsMethodCompletionRequest, $sessionSecret);
        $this->assertNotNull($responseSessionDetailsWithSecret);

    }

}
