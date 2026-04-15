<?php

namespace Checkout\Tests\AgenticCommerce;

use Checkout\AgenticCommerce\Entities\DelegatedPaymentAllowance;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentAllowanceReason;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentBillingAddress;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentCardNumberType;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentCardType;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentHeaders;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentMethodCard;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentRiskSignal;
use Checkout\AgenticCommerce\Requests\DelegatedPaymentRequest;
use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use DateTime;

class AgenticCommerceIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentToken()
    {
        $this->markTestSkipped("requires approval for Agentic Commerce beta features and valid signing key");

        $request = $this->buildValidDelegatedPaymentRequest();
        $headers = $this->buildValidDelegatedPaymentHeaders();

        $response = $this->checkoutApi->getAgenticCommerceClient()->createDelegatedPaymentToken($request, $headers);

        $this->validateCreateDelegatedPaymentTokenResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentTokenWithIdempotencyKey()
    {
        $this->markTestSkipped("requires approval for Agentic Commerce beta features and valid signing key");

        $request = $this->buildValidDelegatedPaymentRequest();
        $headers = $this->buildValidDelegatedPaymentHeaders();
        $idempotencyKey = $this->idempotencyKey();

        $response = $this->checkoutApi->getAgenticCommerceClient()->createDelegatedPaymentToken($request, $headers, $idempotencyKey);

        $this->validateCreateDelegatedPaymentTokenResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentTokenWithBillingAddress()
    {
        $this->markTestSkipped("requires approval for Agentic Commerce beta features and valid signing key");

        $request = $this->buildDelegatedPaymentRequestWithBillingAddress();
        $headers = $this->buildValidDelegatedPaymentHeaders();

        $response = $this->checkoutApi->getAgenticCommerceClient()->createDelegatedPaymentToken($request, $headers);

        $this->validateCreateDelegatedPaymentTokenResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentTokenWithNetworkToken()
    {
        $this->markTestSkipped("requires approval for Agentic Commerce beta features, valid network token, and valid signing key");

        $request = $this->buildDelegatedPaymentRequestWithNetworkToken();
        $headers = $this->buildValidDelegatedPaymentHeaders();

        $response = $this->checkoutApi->getAgenticCommerceClient()->createDelegatedPaymentToken($request, $headers);

        $this->validateCreateDelegatedPaymentTokenResponse($response, $request);
    }

    // Common builder methods for DRY setup
    private function buildValidDelegatedPaymentRequest(): DelegatedPaymentRequest
    {
        $paymentMethod = new DelegatedPaymentMethodCard();
        $paymentMethod->type = DelegatedPaymentCardType::$card;
        $paymentMethod->card_number_type = DelegatedPaymentCardNumberType::$fpan;
        $paymentMethod->number = "4242424242424242";
        $paymentMethod->exp_month = "12";
        $paymentMethod->exp_year = "2025";

        $allowance = new DelegatedPaymentAllowance();
        $allowance->reason = DelegatedPaymentAllowanceReason::$one_time;
        $allowance->max_amount = 5000;
        $allowance->currency = "USD";
        $allowance->merchant_id = "ent_spm7j6blztdeuubnl5h7f6u5qu";
        $allowance->checkout_session_id = uniqid("sess_");
        $allowance->expires_at = (new DateTime('+1 hour'))->format('c');

        $riskSignal = new DelegatedPaymentRiskSignal();
        $riskSignal->type = "card_testing";
        $riskSignal->score = 5;
        $riskSignal->action = "allow";

        $request = new DelegatedPaymentRequest();
        $request->payment_method = $paymentMethod;
        $request->allowance = $allowance;
        $request->risk_signals = [$riskSignal];
        $request->metadata = ["test" => "agentic_commerce_integration"];

        return $request;
    }

    private function buildDelegatedPaymentRequestWithBillingAddress(): DelegatedPaymentRequest
    {
        $request = $this->buildValidDelegatedPaymentRequest();
        
        $billingAddress = new DelegatedPaymentBillingAddress();
        $billingAddress->line_one = "123 Test Street";
        $billingAddress->city = "London";
        $billingAddress->country = "GB";
        $billingAddress->postal_code = "SW1A 1AA";
        
        $request->billing_address = $billingAddress;
        
        return $request;
    }

    private function buildDelegatedPaymentRequestWithNetworkToken(): DelegatedPaymentRequest
    {
        $request = $this->buildValidDelegatedPaymentRequest();
        $request->payment_method->card_number_type = DelegatedPaymentCardNumberType::$network_token;
        $request->payment_method->number = "4111111111111111"; // Replace with actual network token
        return $request;
    }

    private function buildValidDelegatedPaymentHeaders(): DelegatedPaymentHeaders
    {
        // Note: In real integration tests, these would be generated with actual cryptographic operations
        $timestamp = (new DateTime())->format('c');
        
        $headers = new DelegatedPaymentHeaders();
        $headers->signature = $this->generateValidSignature(); // Must be implemented with actual HMAC-SHA256
        $headers->timestamp = $timestamp;
        $headers->api_version = "2024-11-01";
        
        return $headers;
    }

    private function generateValidSignature(): string
    {
        // Note: This is a placeholder. Real implementation would:
        // 1. Concatenate timestamp + JSON request body
        // 2. Compute HMAC-SHA256 with shared signing key
        // 3. Base64-encode the result
        return base64_encode("mock_signature_for_integration_test");
    }

    private function validateCreateDelegatedPaymentTokenResponse(array $response, DelegatedPaymentRequest $originalRequest): void
    {
        $this->assertResponse($response, "id", "created");
        
        $this->assertEquals("vt", substr($response["id"], 0, 2)); // Token ID prefix
        $this->assertNotEmpty($response["created"]);
        
        if (isset($response["metadata"])) {
            $this->assertTrue(is_array($response["metadata"]));
        }
    }
}
