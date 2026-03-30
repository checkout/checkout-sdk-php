<?php

namespace Checkout\Tests\Payments;

use Checkout\CheckoutApiException;
use Checkout\Payments\ReversePaymentRequest;
use Checkout\PlatformType;

class ReversePaymentsIntegrationTest extends AbstractPaymentsIntegrationTest
{
    /**
     * @before
     * @throws \Checkout\CheckoutAuthorizationException
     * @throws \Checkout\CheckoutArgumentException
     * @throws \Checkout\CheckoutException
     */
    public function before(): void
    {
        $this->markTestSkipped("Avoid creating payments all the time");
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReversePayment()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $reversalRequest = new ReversePaymentRequest();
        $reversalRequest->reference = uniqid("reverse_");
        $reversalRequest->metadata = [
            "test_key" => "test_value",
            "reversal_reason" => "customer_request"
        ];

        $response = $this->checkoutApi->getPaymentsClient()->reversePayment(
            $paymentResponse["id"],
            $reversalRequest
        );

        $this->assertResponse($response, "action_id", "_links");
        $this->assertArrayHasKey("payment", $response["_links"]);

        // Check if response indicates success (200 = already reversed, 202 = accepted)
        if (array_key_exists("http_metadata", $response)) {
            $statusCode = $response["http_metadata"]->getStatusCode();
            $this->assertTrue(
                in_array($statusCode, [200, 202]),
                "Expected reversal to return 200 or 202, got: " . $statusCode
            );
            
            if ($statusCode == 202 && array_key_exists("reference", $response)) {
                $this->assertEquals($reversalRequest->reference, $response["reference"]);
            }
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldReversePaymentIdempotently()
    {
        $paymentResponse = $this->makeCardPayment(true);

        $reversalRequest = new ReversePaymentRequest();
        $reversalRequest->reference = uniqid("reverse_idem_");
        $reversalRequest->metadata = ["idempotency_test" => true];
        $idempotencyKey = uniqid("idem_");

        $response1 = $this->checkoutApi->getPaymentsClient()->reversePayment(
            $paymentResponse["id"],
            $reversalRequest,
            $idempotencyKey
        );

        $response2 = $this->checkoutApi->getPaymentsClient()->reversePayment(
            $paymentResponse["id"],
            $reversalRequest,
            $idempotencyKey
        );

        // Both responses should be identical due to idempotency
        $this->assertEquals(
            $response1["action_id"],
            $response2["action_id"],
            "Idempotent requests should return same action_id"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleReversePaymentErrors()
    {
        // Use invalid payment ID to test error handling
        $reversalRequest = new ReversePaymentRequest();
        $reversalRequest->reference = uniqid("reverse_error_");

        try {
            $this->checkoutApi->getPaymentsClient()->reversePayment(
                "pay_invalid_payment_id",
                $reversalRequest
            );
            $this->fail("Expected CheckoutApiException for invalid payment ID");
        } catch (CheckoutApiException $ex) {
            $statusCode = $ex->http_metadata->getStatusCode();
            $this->assertTrue(
                in_array($statusCode, [404, 422]),
                "Expected 404 or 422 for invalid payment ID, got: " . $statusCode
            );
        }
    }
}
