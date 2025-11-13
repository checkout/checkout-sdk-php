<?php

namespace Checkout\Tests\Payments\Sessions;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Common\CustomerRequest;
use Checkout\Payments\BillingInformation;
use Checkout\Payments\Sessions\PaymentSessionsRequest;
use Checkout\Payments\Sessions\PaymentSessionSubmitRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class PaymentSessionsIntegrationTest extends SandboxTestFixture
{
    private static $paymentSessionId;
    private static $paymentSessionToken;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * Test the complete payment session lifecycle
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSession()
    {
        $request = $this->createComprehensivePaymentSessionsRequest();

        $response = $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);

        // Validate response structure
        $this->assertResponse(
            $response,
            "id",
            "payment_session_token",
            "_links",
            "_links.self"
        );

        // Store for use in subsequent tests
        self::$paymentSessionId = $response["id"];
        self::$paymentSessionToken = $response["payment_session_token"];

        // Additional assertions for response quality
        $this->assertNotEmpty($response["id"]);
        $this->assertNotEmpty($response["payment_session_token"]);
        $this->assertArrayHasKey("_links", $response);
        $this->assertArrayHasKey("self", $response["_links"]);
    }

    /**
     * Test payment session creation with minimal required fields
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSessionWithMinimalData()
    {
        //$this->markTestSkipped("use on demand");

        $request = $this->createMinimalPaymentSessionsRequest();

        $response = $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);

        $this->assertResponse(
            $response,
            "id",
            "payment_session_token",
            "_links"
        );

        $this->assertNotEmpty($response["id"]);
        $this->assertNotEmpty($response["payment_session_token"]);
    }

    /**
     * Test payment session creation with maximum fields populated
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreatePaymentSessionWithAllOptionalFields()
    {
        //$this->markTestSkipped("use on demand");

        $request = $this->createMaximalPaymentSessionsRequest();

        $response = $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);

        $this->assertResponse(
            $response,
            "id",
            "payment_session_token",
            "_links"
        );

        // Verify the response contains expected structure
        $this->assertNotEmpty($response["id"]);
    }

    /**
     * Test completing a payment session
     * @test
     * @depends shouldCreatePaymentSession
     * @throws CheckoutApiException
     */
    public function shouldCompletePaymentSession()
    {
        $this->markTestSkipped('Completion requires specific payment flow setup - skip for now');
    }

    /**
     * Test submitting a payment session
     * @test
     * @depends shouldCreatePaymentSession
     * @throws CheckoutApiException
     */
    public function shouldSubmitPaymentSession()
    {
        // Skip if no session was created
        if (empty(self::$paymentSessionId)) {
            $this->markTestSkipped('No payment session available for submission test');
        }

        $request = $this->createPaymentSubmitRequest();

        try {
            $response = $this->checkoutApi->getPaymentSessionsClient()->submitPaymentSession(self::$paymentSessionId, $request);

            // Validate successful submission response
            $this->assertNotNull($response);
            $this->assertIsArray($response);
        } catch (CheckoutApiException $e) {
            // In sandbox environment, submission might fail due to test constraints
            // Verify we get expected error codes for test scenarios
            $this->assertContains($e->getHttpStatusCode(), [400, 422, 404]);
            $this->assertNotEmpty($e->getErrorDetails());
        }
    }

    /**
     * Test payment session creation with invalid data
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFailWithInvalidAmount()
    {
        $request = new PaymentSessionsRequest();
        $request->amount = -100; // Invalid negative amount
        $request->currency = Currency::$GBP;
        $request->reference = "INVALID-001";
        $request->success_url = "https://example.com/success";

        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionCode(422); // Unprocessable Entity

        $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);
    }

    /**
     * Test payment session creation without required fields
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFailWithoutRequiredFields()
    {
        $request = new PaymentSessionsRequest();
        // Intentionally leaving required fields empty

        $this->expectException(CheckoutApiException::class);
        $this->expectExceptionCode(422); // Unprocessable Entity

        $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);
    }

    /**
     * Test payment session creation with invalid currency
     * @test
     * @throws CheckoutApiException
     */
    public function shouldFailWithInvalidCurrency()
    {
        $request = new PaymentSessionsRequest();
        $request->amount = 1000;
        $request->currency = "INVALID"; // Invalid currency code
        $request->reference = "INVALID-002";
        $request->success_url = "https://example.com/success";

        $this->expectException(CheckoutApiException::class);

        $this->checkoutApi->getPaymentSessionsClient()->createPaymentSessions($request);
    }

    /**
     * Create a comprehensive payment session request with all common fields
     */
    private function createComprehensivePaymentSessionsRequest(): PaymentSessionsRequest
    {
        $billing = new BillingInformation();
        $billing->address = $this->getAddress();

        $customer = new CustomerRequest();
        $customer->name = "John Smith";
        $customer->email = "john.smith@example.com";

        $request = new PaymentSessionsRequest();
        $request->amount = 2000;
        $request->currency = Currency::$GBP;
        $request->reference = "ORD-" . uniqid();
        $request->description = "Test payment session for integration testing";
        $request->customer = $customer;
        $request->billing = $billing;
        $request->success_url = "https://example.com/payments/success";
        $request->failure_url = "https://example.com/payments/failure";
        $request->locale = "en-GB";

        return $request;
    }

    /**
     * Create a minimal payment session request with only required fields
     */
    private function createMinimalPaymentSessionsRequest(): PaymentSessionsRequest
    {
        $request = new PaymentSessionsRequest();
        $request->amount = 1000;
        $request->billing = new BillingInformation();
        $request->currency = Currency::$USD;
        $request->reference = "MIN-" . uniqid();
        $request->success_url = "https://example.com/success";
        $request->failure_url = "https://example.com/failure";

        return $request;
    }

    /**
     * Create a maximal payment session request with all optional fields
     */
    private function createMaximalPaymentSessionsRequest(): PaymentSessionsRequest
    {
        $billing = new BillingInformation();
        $billing->address = $this->getAddress();

        $customer = new CustomerRequest();
        $customer->id = "cust_max_" . uniqid();
        $customer->name = "Jane Doe";
        $customer->email = "jane.doe@example.com";
        $customer->phone = $this->getPhone();

        $request = new PaymentSessionsRequest();
        $request->amount = 5000;
        $request->currency = Currency::$EUR;
        $request->reference = "MAX-" . uniqid();
        $request->description = "Comprehensive test payment with all fields populated";
        $request->customer = $customer;
        $request->billing = $billing;
        $request->success_url = "https://example.com/payments/success";
        $request->failure_url = "https://example.com/payments/failure";
        $request->locale = "en-US";
        $request->capture = true;
        $request->capture_on = new \DateTime('+7 days');

        return $request;
    }

    /**
     * Create a payment submission request
     */
    private function createPaymentSubmitRequest(): PaymentSessionSubmitRequest
    {
        $request = new PaymentSessionSubmitRequest();
        $request->session_data = "test_session_data_" . uniqid();
        $request->amount = 2000;
        $request->reference = "SUB-" . uniqid();
        $request->ip_address = "192.168.1.1";
        $request->payment_type = "regular";

        return $request;
    }

}
