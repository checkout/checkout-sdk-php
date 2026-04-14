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
        $request = new SimulateOobAuthenticationRequest();
        $request->transaction_id = "txi_12345";
        $request->outcome = "authentication_successful";

        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildMinimalSimulateOobAuthenticationRequest()
    {
        $request = new SimulateOobAuthenticationRequest();
        $request->transaction_id = "txi_minimal";
        $request->outcome = "authentication_failed";

        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildCompleteSimulateOobAuthenticationRequest()
    {
        $request = new SimulateOobAuthenticationRequest();
        $request->transaction_id = "txi_complete";
        $request->outcome = "authentication_successful";

        return $request;
    }

    private function buildSimulateOobAuthenticationResponse(): array
    {
        return [
            "transaction_id" => "txi_12345",
            "outcome" => "authentication_successful",
            "status" => "completed"
        ];
    }

    private function buildMinimalSimulateOobAuthenticationResponse(): array
    {
        return [
            "transaction_id" => "txi_minimal",
            "outcome" => "authentication_failed",
            "status" => "completed"
        ];
    }

    private function buildCompleteSimulateOobAuthenticationResponse(): array
    {
        return [
            "transaction_id" => "txi_complete",
            "outcome" => "authentication_successful",
            "status" => "completed",
            "authentication_details" => [
                "method" => "sms",
                "timestamp" => "2023-12-01T10:00:00Z"
            ]
        ];
    }

    private function validateSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("transaction_id", $response);
        $this->assertArrayHasKey("outcome", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertEquals("txi_12345", $response["transaction_id"]);
        $this->assertEquals("authentication_successful", $response["outcome"]);
        $this->assertEquals("completed", $response["status"]);
    }

    private function validateMinimalSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("transaction_id", $response);
        $this->assertArrayHasKey("outcome", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertEquals("txi_minimal", $response["transaction_id"]);
        $this->assertEquals("authentication_failed", $response["outcome"]);
        $this->assertEquals("completed", $response["status"]);
    }

    private function validateCompleteSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("transaction_id", $response);
        $this->assertArrayHasKey("outcome", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("authentication_details", $response);
        
        $this->assertEquals("txi_complete", $response["transaction_id"]);
        $this->assertEquals("authentication_successful", $response["outcome"]);
        $this->assertEquals("completed", $response["status"]);
        
        $this->assertTrue(is_array($response["authentication_details"]));
        $this->assertArrayHasKey("method", $response["authentication_details"]);
        $this->assertArrayHasKey("timestamp", $response["authentication_details"]);
        $this->assertEquals("sms", $response["authentication_details"]["method"]);
        $this->assertEquals("2023-12-01T10:00:00Z", $response["authentication_details"]["timestamp"]);
    }
}
