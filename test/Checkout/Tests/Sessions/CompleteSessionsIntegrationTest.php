<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutAuthorizationException;

class CompleteSessionsIntegrationTest extends AbstractSessionsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     * @throws CheckoutAuthorizationException
     */
    public function shouldTryToCompleteCardSessionBrowserSession()
    {
        $sessionResponse = $this->createHostedSession();

        $this->assertNotNull($sessionResponse);

        $sessionId = $sessionResponse["id"];
        $sessionSecret = $sessionResponse["session_secret"];

        try {
            $this->checkoutApi->getSessionsClient()->completeSession($sessionId);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_403, $e->getMessage());
            $this->assertNotEmpty($e->http_metadata->getHeaders()["Cko-Request-Id"]);
            $this->assertNotNull($e->error_details);
        }

        try {
            $this->checkoutApi->getSessionsClient()->completeSession($sessionId, $sessionSecret);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_403, $e->getMessage());
            $this->assertNotEmpty($e->http_metadata->getHeaders()["Cko-Request-Id"]);
            $this->assertNotNull($e->error_details);
        }
    }
}
