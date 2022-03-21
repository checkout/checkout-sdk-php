<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;

class CompleteSessionsIntegrationTest extends AbstractSessionsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldTryToCompleteCardSessionBrowserSession()
    {
        $sessionResponse = $this->createHostedSession();

        $this->assertNotNull($sessionResponse);

        $sessionId = $sessionResponse["id"];
        $sessionSecret = $sessionResponse["session_secret"];

        try {
            $this->fourApi->getSessionsClient()->completeSession($sessionId);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_403, $e->getMessage());
            $this->assertNotEmpty($e->request_id);
            $this->assertNotNull($e->error_details);
        }

        try {
            $this->fourApi->getSessionsClient()->completeSession($sessionId, $sessionSecret);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_403, $e->getMessage());
            $this->assertNotEmpty($e->request_id);
            $this->assertNotNull($e->error_details);
        }

    }
}
