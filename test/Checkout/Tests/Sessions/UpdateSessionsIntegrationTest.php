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
    public function shouldUpdateCardSessionUsingIdBrowserSession()
    {
        $responseHostedSession = $this->createHostedSession();

        $this->assertNotNull($responseHostedSession);

        $sessionId = $responseHostedSession["id"];

        $responseSessionDetails = $this->retriable(
            function () use ($sessionId) {
                return $this->checkoutApi->getSessionsClient()->updateSession($sessionId, $this->getBrowserSession());
            }
        );

        $this->assertNotNull($responseSessionDetails);
        self::assertArrayHasKey("http_metadata", $responseSessionDetails);
        self::assertEquals(200, $responseSessionDetails["http_metadata"]->getStatusCode());
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateCardSessionUsingSessionSecretBrowserSession()
    {
        $responseHostedSession = $this->createHostedSession();

        $this->assertNotNull($responseHostedSession);

        $sessionId = $responseHostedSession["id"];
        $sessionSecret = $responseHostedSession["session_secret"];

        $responseSessionDetailsWithSecret = $this->retriable(
            function () use ($sessionSecret, $sessionId) {
                return $this->checkoutApi->getSessionsClient()->updateSession(
                    $sessionId,
                    $this->getBrowserSession(),
                    $sessionSecret
                );
            }
        );
        $this->assertNotNull($responseSessionDetailsWithSecret);
        self::assertArrayHasKey("http_metadata", $responseSessionDetailsWithSecret);
        self::assertEquals(200, $responseSessionDetailsWithSecret["http_metadata"]->getStatusCode());
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdateCardSessionAppSession()
    {
        $responseHostedSession = $this->createHostedSession();
        $this->assertNotNull($responseHostedSession);

        $sessionId = $responseHostedSession["id"];
        $responseSessionDetails = $this->retriable(
            function () use ($sessionId) {
                return $this->checkoutApi->getSessionsClient()->updateSession($sessionId, $this->getAppSession());
            }
        );
        $this->assertNotNull($responseSessionDetails);
        self::assertArrayHasKey("http_metadata", $responseSessionDetails);
        self::assertEquals(200, $responseSessionDetails["http_metadata"]->getStatusCode());
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdate3dsMethodCompletionIndicatorSessionId()
    {
        $responseHostedSession = $this->createHostedSession();
        $this->assertNotNull($responseHostedSession);

        $threeDsMethodCompletionRequest = new ThreeDsMethodCompletionRequest();
        $threeDsMethodCompletionRequest->three_ds_method_completion = ThreeDsMethodCompletion::$y;

        $sessionId = $responseHostedSession["id"];

        $responseSessionDetails = $this->retriable(
            function () use ($threeDsMethodCompletionRequest, $sessionId) {
                return $this->checkoutApi->getSessionsClient()->updateThreeDsMethodCompletionIndicator(
                    $sessionId,
                    $threeDsMethodCompletionRequest
                );
            }
        );
        $this->assertNotNull($responseSessionDetails);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldUpdate3dsMethodCompletionIndicatorSessionSecret()
    {
        $responseHostedSession = $this->createHostedSession();
        $this->assertNotNull($responseHostedSession);

        $threeDsMethodCompletionRequest = new ThreeDsMethodCompletionRequest();
        $threeDsMethodCompletionRequest->three_ds_method_completion = ThreeDsMethodCompletion::$y;

        $sessionId = $responseHostedSession["id"];
        $sessionSecret = $responseHostedSession["session_secret"];

        $responseSessionDetailsWithSecret = $this->retriable(
            function () use ($sessionSecret, $threeDsMethodCompletionRequest, $sessionId) {
                return $this->checkoutApi->getSessionsClient()->updateThreeDsMethodCompletionIndicator(
                    $sessionId,
                    $threeDsMethodCompletionRequest,
                    $sessionSecret
                );
            }
        );
        $this->assertNotNull($responseSessionDetailsWithSecret);
    }
}
