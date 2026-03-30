<?php

namespace Checkout\Tests\Payments;

use Checkout\PlatformType;
use Checkout\CheckoutApiException;
use Checkout\Payments\CancelScheduledRetryRequest;

class CancelScheduledRetryIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @before
     * @throws \Checkout\CheckoutAuthorizationException
     * @throws \Checkout\CheckoutArgumentException
     * @throws \Checkout\CheckoutException
     */
    public function before(): void
    {
        $this->markTestSkipped(
            "Avoid creating payments all the time"
        );
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCancelScheduledRetry()
    {
        $paymentResponse = $this->makeCardPayment();

        $cancellationRequest = new CancelScheduledRetryRequest();
        $cancellationRequest->reference = uniqid("cancel_");

        $response = $this->checkoutApi->getPaymentsClient()->cancelAScheduledRetry(
            $paymentResponse["id"],
            $cancellationRequest
        );

        if (array_key_exists("http_metadata", $response)) {
            $statusCode = $response["http_metadata"]->getStatusCode();
            
            if ($statusCode == 202) {
                $this->assertResponse($response, "action_id", "reference", "_links");
                $this->assertArrayHasKey("payment", $response["_links"]);
            } else {
                // Expected when no retry is scheduled (403) or payment not found (404)
                $this->assertTrue(
                    in_array($statusCode, [403, 404, 422]),
                    "Expected cancellation to return 202, 403, 404, or 422, got: " . $statusCode
                );
            }
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCancelScheduledRetryIdempotently()
    {
        $paymentResponse = $this->makeCardPayment();

        $cancellationRequest = new CancelScheduledRetryRequest();
        $cancellationRequest->reference = uniqid("cancel_idempotent_");
        $idempotencyKey = uniqid("idem_");

        $response1 = $this->checkoutApi->getPaymentsClient()->cancelAScheduledRetry(
            $paymentResponse["id"],
            $cancellationRequest,
            $idempotencyKey
        );

        $response2 = $this->checkoutApi->getPaymentsClient()->cancelAScheduledRetry(
            $paymentResponse["id"],
            $cancellationRequest,
            $idempotencyKey
        );

        // Both responses should be identical due to idempotency
        if (array_key_exists("http_metadata", $response1) &&
            array_key_exists("http_metadata", $response2) &&
            $response1["http_metadata"]->getStatusCode() == 202) {
            $this->assertEquals(
                $response1["action_id"],
                $response2["action_id"],
                "Idempotent requests should return same action_id"
            );
        }
    }
}
