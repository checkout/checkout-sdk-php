<?php

namespace Checkout\Tests\Issuing\Testing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\Issuing\Testing\SimulateRefundRequest;
use Checkout\Issuing\Testing\SimulateOobAuthenticationRequest;
use Checkout\Issuing\Testing\OobSimulateTransactionDetails;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class TestingClientTest extends UnitTestFixture
{
    /**
     * @var IssuingClient
     */
    private $client;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new IssuingClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateAuthorization()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Authorized"
            ]);

        $request = new CardAuthorizationRequest();
        $response = $this->client->simulateAuthorization($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateIncrement()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Authorized"
            ]);

        $request = new CardIncrementAuthorizationRequest();
        $response = $this->client->simulateIncrementingAuthorization("auth_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateClearing()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Cleared"
            ]);

        $request = new CardClearingAuthorizationRequest();
        $response = $this->client->simulateClearing("auth_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateReversal()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Reversed"
            ]);

        $request = new CardReversalAuthorizationRequest();
        $response = $this->client->simulateReversal("auth_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateRefund()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([]);

        $request = $this->buildSimulateRefundRequest();
        $response = $this->client->simulateRefund("auth_12345", $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateOobAuthentication()
    {
        $this->apiClient
            ->method("post")
            ->willReturn($this->buildSimulateOobAuthenticationResponse());

        $request = $this->buildSimulateOobAuthenticationRequest();
        $response = $this->client->simulateOobAuthentication($request);

        $this->validateSimulateOobAuthenticationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateOobAuthenticationWithMinimalData()
    {
        $this->apiClient
            ->method("post")
            ->willReturn($this->buildMinimalSimulateOobAuthenticationResponse());

        $request = $this->buildMinimalSimulateOobAuthenticationRequest();
        $response = $this->client->simulateOobAuthentication($request);

        $this->validateMinimalSimulateOobAuthenticationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateOobAuthenticationWithCompleteDetails()
    {
        $this->apiClient
            ->method("post")
            ->willReturn($this->buildCompleteSimulateOobAuthenticationResponse());

        $request = $this->buildCompleteSimulateOobAuthenticationRequest();
        $response = $this->client->simulateOobAuthentication($request);

        $this->validateCompleteSimulateOobAuthenticationResponse($response);
    }

    /**
     * @return SimulateRefundRequest
     */
    private function buildSimulateRefundRequest()
    {
        $request = new SimulateRefundRequest();
        $request->amount = 1000;

        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildSimulateOobAuthenticationRequest()
    {
        $transactionDetails = new OobSimulateTransactionDetails();
        $transactionDetails->merchant_name = "Test Merchant";
        $transactionDetails->purchase_amount = 100.50;
        $transactionDetails->purchase_currency = "USD";
        $transactionDetails->last_four = "1234";

        $request = new SimulateOobAuthenticationRequest();
        $request->card_id = "crd_12345678901234567890123456";
        $request->transaction_details = $transactionDetails;

        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildMinimalSimulateOobAuthenticationRequest()
    {
        $request = new SimulateOobAuthenticationRequest();
        $request->card_id = "crd_minimal123456789012345678";

        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildCompleteSimulateOobAuthenticationRequest()
    {
        $transactionDetails = new OobSimulateTransactionDetails();
        $transactionDetails->merchant_name = "Complete Test Merchant";
        $transactionDetails->purchase_amount = 250.75;
        $transactionDetails->purchase_currency = "EUR";
        $transactionDetails->last_four = "5678";

        $request = new SimulateOobAuthenticationRequest();
        $request->card_id = "crd_complete12345678901234567890";
        $request->transaction_details = $transactionDetails;

        return $request;
    }

    private function buildSimulateOobAuthenticationResponse(): array
    {
        return [
            "status" => "completed",
            "authentication_result" => "success",
            "card_id" => "crd_12345678901234567890123456"
        ];
    }

    private function buildMinimalSimulateOobAuthenticationResponse(): array
    {
        return [
            "status" => "completed",
            "authentication_result" => "failed",
            "card_id" => "crd_minimal123456789012345678"
        ];
    }

    private function buildCompleteSimulateOobAuthenticationResponse(): array
    {
        return [
            "status" => "completed",
            "authentication_result" => "success",
            "card_id" => "crd_complete12345678901234567890",
            "transaction_details" => [
                "merchant_name" => "Complete Test Merchant",
                "purchase_amount" => 250.75,
                "purchase_currency" => "EUR",
                "last_four" => "5678"
            ]
        ];
    }

    private function validateSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("authentication_result", $response);
        $this->assertArrayHasKey("card_id", $response);
        $this->assertEquals("completed", $response["status"]);
        $this->assertEquals("success", $response["authentication_result"]);
        $this->assertEquals("crd_12345678901234567890123456", $response["card_id"]);
    }

    private function validateMinimalSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("authentication_result", $response);
        $this->assertArrayHasKey("card_id", $response);
        $this->assertEquals("completed", $response["status"]);
        $this->assertEquals("failed", $response["authentication_result"]);
        $this->assertEquals("crd_minimal123456789012345678", $response["card_id"]);
    }

    private function validateCompleteSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("authentication_result", $response);
        $this->assertArrayHasKey("card_id", $response);
        $this->assertArrayHasKey("transaction_details", $response);
        
        $this->assertEquals("completed", $response["status"]);
        $this->assertEquals("success", $response["authentication_result"]);
        $this->assertEquals("crd_complete12345678901234567890", $response["card_id"]);
        
        $this->assertTrue(is_array($response["transaction_details"]));
        $this->assertArrayHasKey("merchant_name", $response["transaction_details"]);
        $this->assertArrayHasKey("purchase_amount", $response["transaction_details"]);
        $this->assertArrayHasKey("purchase_currency", $response["transaction_details"]);
        $this->assertArrayHasKey("last_four", $response["transaction_details"]);
        $this->assertEquals("Complete Test Merchant", $response["transaction_details"]["merchant_name"]);
        $this->assertEquals(250.75, $response["transaction_details"]["purchase_amount"]);
        $this->assertEquals("EUR", $response["transaction_details"]["purchase_currency"]);
        $this->assertEquals("5678", $response["transaction_details"]["last_four"]);
    }
}
