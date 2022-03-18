<?php

namespace Checkout\Tests\Sessions;

use Checkout\CheckoutApiException;

class CompleteSessionsIntegrationTest extends AbstractSessionsIntegrationTest
{

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldTryToCompleteCardSessionBrowserSession(): void
    {
        $sessionResponse = $this->createHostedSession();

        $this->assertNotNull($sessionResponse);

        $sessionId = $sessionResponse["id"];
        $sessionSecret = $sessionResponse["session_secret"];

        try {
            $this->fourApi->getSessionsClient()->completeSession($sessionId);
            self::fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            self::assertEquals(self::MESSAGE_403, $e->getMessage());
            self::assertNotEmpty($e->request_id);
            self::assertNotNull($e->error_details);
        }

        try {
            $this->fourApi->getSessionsClient()->completeSession($sessionId, $sessionSecret);
            self::fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            self::assertEquals(self::MESSAGE_403, $e->getMessage());
            self::assertNotEmpty($e->request_id);
            self::assertNotNull($e->error_details);
        }

    }
}
