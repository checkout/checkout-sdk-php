<?php

namespace Checkout\Tests\AgenticCommerce;

use Checkout\AgenticCommerce\AgenticCommerceClient;
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
use Checkout\CheckoutArgumentException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;
use DateTime;
use TypeError;

class AgenticCommerceClientTest extends UnitTestFixture
{
    /**
     * @var AgenticCommerceClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new AgenticCommerceClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentToken()
    {
        $request = $this->buildValidDelegatedPaymentRequest();
        $headers = $this->buildValidDelegatedPaymentHeaders();
        $idempotencyKey = "idempotency_123";

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("agentic_commerce/delegate_payment"),
                $this->equalTo($request),
                $this->anything(),
                $this->equalTo($idempotencyKey),
                $this->equalTo($headers)
            )
            ->willReturn($this->buildCreateDelegatedPaymentTokenResponse());

        $response = $this->client->createDelegatedPaymentToken($request, $headers, $idempotencyKey);

        $this->validateCreateDelegatedPaymentTokenResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentTokenWithoutIdempotencyKey()
    {
        $request = $this->buildValidDelegatedPaymentRequest();
        $headers = $this->buildValidDelegatedPaymentHeaders();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("agentic_commerce/delegate_payment"),
                $this->equalTo($request),
                $this->anything(),
                $this->equalTo(null),
                $this->equalTo($headers)
            )
            ->willReturn($this->buildCreateDelegatedPaymentTokenResponse());

        $response = $this->client->createDelegatedPaymentToken($request, $headers);

        $this->validateCreateDelegatedPaymentTokenResponse($response);
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenRequestIsNull()
    {
        $this->expectException(TypeError::class);
        $this->client->createDelegatedPaymentToken(null, $this->buildValidDelegatedPaymentHeaders());
    }

    /**
     * @test
     */
    public function shouldThrowExceptionWhenHeadersAreNull()
    {
        $this->expectException(TypeError::class);
        $this->client->createDelegatedPaymentToken($this->buildValidDelegatedPaymentRequest(), null);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentTokenWithNetworkToken()
    {
        $request = $this->buildDelegatedPaymentRequestWithNetworkToken();
        $headers = $this->buildValidDelegatedPaymentHeaders();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("agentic_commerce/delegate_payment"),
                $this->equalTo($request),
                $this->anything(),
                $this->anything(),
                $this->equalTo($headers)
            )
            ->willReturn($this->buildCreateDelegatedPaymentTokenResponse());

        $response = $this->client->createDelegatedPaymentToken($request, $headers);

        $this->validateCreateDelegatedPaymentTokenResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateDelegatedPaymentTokenWithComplexAllowance()
    {
        $request = $this->buildDelegatedPaymentRequestWithComplexAllowance();
        $headers = $this->buildValidDelegatedPaymentHeaders();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("agentic_commerce/delegate_payment"),
                $this->equalTo($request),
                $this->anything(),
                $this->anything(),
                $this->equalTo($headers)
            )
            ->willReturn($this->buildCreateDelegatedPaymentTokenResponse());

        $response = $this->client->createDelegatedPaymentToken($request, $headers);

        $this->validateCreateDelegatedPaymentTokenResponse($response);
    }

    // Common builder methods for DRY setup
    private function buildValidDelegatedPaymentRequest(): DelegatedPaymentRequest
    {
        $paymentMethod = new DelegatedPaymentMethodCard();
        $paymentMethod->type = DelegatedPaymentCardType::$card;
        $paymentMethod->card_number_type = DelegatedPaymentCardNumberType::$fpan;
        $paymentMethod->number = "4242424242424242";
        $paymentMethod->exp_month = "11";
        $paymentMethod->exp_year = "2026";
        $paymentMethod->metadata = ["issuing_bank" => "test"];

        $allowance = new DelegatedPaymentAllowance();
        $allowance->reason = DelegatedPaymentAllowanceReason::$one_time;
        $allowance->max_amount = 10000;
        $allowance->currency = "USD";
        $allowance->merchant_id = "cli_vkuhvk4vjn2edkps7dfsq6emqm";
        $allowance->checkout_session_id = "1PQrsT";
        $allowance->expires_at = (new DateTime('+1 hour'))->format('c');

        $riskSignal = new DelegatedPaymentRiskSignal();
        $riskSignal->type = "card_testing";
        $riskSignal->score = 10;
        $riskSignal->action = "blocked";

        $request = new DelegatedPaymentRequest();
        $request->payment_method = $paymentMethod;
        $request->allowance = $allowance;
        $request->risk_signals = [$riskSignal];
        $request->metadata = ["campaign" => "q4"];

        return $request;
    }

    private function buildDelegatedPaymentRequestWithNetworkToken(): DelegatedPaymentRequest
    {
        $request = $this->buildValidDelegatedPaymentRequest();
        $request->payment_method->card_number_type = DelegatedPaymentCardNumberType::$network_token;
        $request->payment_method->number = "4242424242424242"; // Network token
        return $request;
    }

    private function buildDelegatedPaymentRequestWithComplexAllowance(): DelegatedPaymentRequest
    {
        $request = $this->buildValidDelegatedPaymentRequest();
        
        // Add billing address
        $billingAddress = new DelegatedPaymentBillingAddress();
        $billingAddress->address_line1 = "123 Main St";
        $billingAddress->city = "New York";
        $billingAddress->country = "US";
        $billingAddress->zip = "10001";
        $request->billing_address = $billingAddress;

        // Multiple risk signals
        $riskSignal1 = $request->risk_signals[0];
        $riskSignal2 = new DelegatedPaymentRiskSignal();
        $riskSignal2->type = "velocity_check";
        $riskSignal2->score = 5;
        $riskSignal2->action = "allow";
        
        $request->risk_signals = [$riskSignal1, $riskSignal2];
        
        return $request;
    }

    private function buildValidDelegatedPaymentHeaders(): DelegatedPaymentHeaders
    {
        $headers = new DelegatedPaymentHeaders();
        $headers->signature = "base64encodedsignature==";
        $headers->timestamp = (new DateTime())->format('c');
        $headers->api_version = "2024-03-11";
        return $headers;
    }

    private function buildCreateDelegatedPaymentTokenResponse(): array
    {
        return [
            "id" => "vt_abc123def456ghi789",
            "created" => "2026-03-11T10:30:00Z",
            "metadata" => [
                "psp" => "checkout.com"
            ]
        ];
    }

    private function validateCreateDelegatedPaymentTokenResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("created", $response);
        $this->assertEquals("vt_abc123def456ghi789", $response["id"]);
        $this->assertEquals("2026-03-11T10:30:00Z", $response["created"]);
        if (isset($response["metadata"])) {
            $this->assertTrue(is_array($response["metadata"]));
        }
    }
}
