<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;
use Checkout\Common\ChallengeIndicatorType;
use Checkout\Sessions\Category;
use Checkout\Sessions\TransactionType;

class RequestAndGetSessionsIntegrationTest extends AbstractSessionsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldRequestAndGetCardSessionBrowserSession()
    {
        $browserSession = $this->getBrowserSession();
        $responseBrowserSession = $this->createNonHostedSession(
            $browserSession,
            Category::$payment,
            ChallengeIndicatorType::$no_preference,
            TransactionType::$goods_service
        );

        $this->assertNotNull($responseBrowserSession);

        $sessionId = $responseBrowserSession["id"];
        $sessionSecret = $responseBrowserSession["session_secret"];

        $responseSessionDetails = $this->checkoutApi->getSessionsClient()->getSessionDetails($sessionId);
        $this->assertNotNull($responseSessionDetails);
        $responseSessionDetailsWithSecret = $this->checkoutApi->getSessionsClient()->getSessionDetails(
            $sessionId,
            $sessionSecret
        );
        $this->assertNotNull($responseSessionDetailsWithSecret);
    }

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldRequestAndGetCardSessionAppSession()
    {
        $appSession = $this->getAppSession();
        $responseNonHostedSession = $this->createNonHostedSession(
            $appSession,
            Category::$payment,
            ChallengeIndicatorType::$no_preference,
            TransactionType::$goods_service
        );

        $this->assertNotNull($responseNonHostedSession);

        $sessionId = $responseNonHostedSession["id"];
        $sessionSecret = $responseNonHostedSession["session_secret"];

        $responseSessionDetails = $this->checkoutApi->getSessionsClient()->getSessionDetails($sessionId);
        $this->assertNotNull($responseSessionDetails);
        $responseSessionDetailsWithSecret = $this->checkoutApi->getSessionsClient()->getSessionDetails(
            $sessionId,
            $sessionSecret
        );
        $this->assertNotNull($responseSessionDetailsWithSecret);
    }
}
