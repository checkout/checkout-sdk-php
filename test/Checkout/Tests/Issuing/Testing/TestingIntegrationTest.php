<?php

namespace Checkout\Tests\Issuing\Testing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\Issuing\Testing\SimulateRefundRequest;
use Checkout\Issuing\Testing\SimulateOobAuthenticationRequest;
use Checkout\Issuing\Testing\OobSimulateTransactionDetails;
use Checkout\Issuing\Testing\CardSimulation;
use Checkout\Issuing\Testing\TransactionSimulation;
use Checkout\Issuing\Testing\TransactionType;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class TestingIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $card;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function beforeAll()
    {
        $this->markTestSkipped("Avoid creating cards all the time");

        $this->before();
        $cardholder = $this->createCardholder();
        $this->card = $this->createCard($cardholder["id"], true);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateAuthorization()
    {
        $authorizationRequest = $this->getAuthorizationRequest();

        $simulationResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authorizationRequest);

        $this->assertResponse(
            $simulationResponse,
            "id",
            "status"
        );
        $this->assertEquals("Authorized", $simulationResponse["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateIncrementingAuthorization()
    {
        $authorizationRequest = $this->getAuthorizationRequest();
        
        // First simulate authorization to get the authorization response with ID
        $authorizationResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authorizationRequest);

        $cardIncrementAuthorizationRequest = new CardIncrementAuthorizationRequest();
        $cardIncrementAuthorizationRequest->amount = 1;

        $cardIncrementAuthorizationResponse = $this->issuingApi->getIssuingClient()->
        simulateIncrementingAuthorization(
            $authorizationResponse["id"],
            $cardIncrementAuthorizationRequest
        );

        $this->assertResponse(
            $cardIncrementAuthorizationResponse,
            "status"
        );
        $this->assertEquals("Authorized", $cardIncrementAuthorizationResponse["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateClearing()
    {
        $authorizationRequest = $this->getAuthorizationRequest();
        
        // First simulate authorization to get the authorization response with ID
        $authorizationResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authorizationRequest);

        $cardClearingAuthorizationRequest = new CardClearingAuthorizationRequest();
        $cardClearingAuthorizationRequest->amount = 1;

        $cardClearingAuthorizationResponse = $this->issuingApi->getIssuingClient()->
        simulateClearing(
            $authorizationResponse["id"],
            $cardClearingAuthorizationRequest
        );

        $this->assertNotNull($cardClearingAuthorizationResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateReversal()
    {
        $authorizationRequest = $this->getAuthorizationRequest();
        
        // First simulate authorization to get the authorization response with ID
        $authorizationResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authorizationRequest);

        $cardReversalAuthorizationRequest = new CardReversalAuthorizationRequest();
        $cardReversalAuthorizationRequest->amount = 1;

        $cardReversalAuthorizationResponse = $this->issuingApi->getIssuingClient()->
        simulateReversal(
            $authorizationResponse["id"],
            $cardReversalAuthorizationRequest
        );

        $this->assertResponse(
            $cardReversalAuthorizationResponse,
            "status"
        );
        $this->assertEquals("Reversed", $cardReversalAuthorizationResponse["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateRefund()
    {
        $authorizationRequest = $this->getAuthorizationRequest();
        $simulationResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authorizationRequest);
        
        // Clear the authorization first
        $clearingRequest = new CardClearingAuthorizationRequest();
        $clearingRequest->amount = 100;
        $this->issuingApi->getIssuingClient()->simulateClearing($simulationResponse["id"], $clearingRequest);
        
        // Now simulate refund
        $refundRequest = $this->buildSimulateRefundRequest();
        $refundResponse = $this->issuingApi->getIssuingClient()->simulateRefund(
            $simulationResponse["id"],
            $refundRequest
        );
        
        $this->assertNotNull($refundResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateOobAuthentication()
    {
        $this->markTestSkipped("requires a valid transaction ID for OOB authentication simulation");

        $request = $this->buildSimulateOobAuthenticationRequest();
        $response = $this->issuingApi->getIssuingClient()->simulateOobAuthentication($request);

        $this->validateSimulateOobAuthenticationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateOobAuthenticationWithFailedOutcome()
    {
        $this->markTestSkipped("requires a valid transaction ID for OOB authentication simulation");

        $request = $this->buildSimulateOobAuthenticationFailedRequest();
        $response = $this->issuingApi->getIssuingClient()->simulateOobAuthentication($request);

        $this->validateSimulateOobAuthenticationFailedResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompleteOobAuthenticationWorkflow()
    {
        $this->markTestSkipped("requires complete OOB authentication workflow setup");

        // Note: This would be a complete workflow test that:
        // 1. Creates a transaction requiring OOB authentication
        // 2. Simulates the OOB authentication process
        // 3. Verifies the authentication outcome
        // 4. Validates the transaction state after authentication

        // For now, this serves as documentation of the expected flow
    }


    /**
     * @return SimulateRefundRequest
     */
    private function buildSimulateRefundRequest()
    {
        $request = new SimulateRefundRequest();
        $request->amount = 50;
        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildSimulateOobAuthenticationRequest()
    {
        $transactionDetails = new OobSimulateTransactionDetails();
        $transactionDetails->merchant_name = "Integration Test Merchant";
        $transactionDetails->purchase_amount = 150.00;
        $transactionDetails->purchase_currency = "USD";
        $transactionDetails->last_four = "1234";

        $request = new SimulateOobAuthenticationRequest();
        $request->card_id = "crd_integration_test_12345678";
        $request->transaction_details = $transactionDetails;
        return $request;
    }

    /**
     * @return SimulateOobAuthenticationRequest
     */
    private function buildSimulateOobAuthenticationFailedRequest()
    {
        $transactionDetails = new OobSimulateTransactionDetails();
        $transactionDetails->merchant_name = "Failed Test Merchant";
        $transactionDetails->purchase_amount = 50.00;
        $transactionDetails->purchase_currency = "EUR";
        $transactionDetails->last_four = "9999";

        $request = new SimulateOobAuthenticationRequest();
        $request->card_id = "crd_integration_test_failed567890";
        $request->transaction_details = $transactionDetails;
        return $request;
    }

    private function validateSimulateOobAuthenticationResponse(array $response): void
    {
        $this->assertResponse($response, "status", "authentication_result", "card_id");
        
        $this->assertEquals("crd_integration_test_12345678", $response["card_id"]);
        $this->assertTrue(in_array($response["status"], ["completed", "pending", "processing"]));
        $this->assertTrue(in_array($response["authentication_result"], ["success", "failed", "pending"]));
        
        // Validate optional fields if present
        if (isset($response["transaction_details"])) {
            $this->assertTrue(is_array($response["transaction_details"]));
        }
        
        if (isset($response["created_time"])) {
            $this->assertNotEmpty($response["created_time"]);
        }
    }

    private function validateSimulateOobAuthenticationFailedResponse(array $response): void
    {
        $this->assertResponse($response, "status", "authentication_result", "card_id");
        
        $this->assertEquals("crd_integration_test_failed567890", $response["card_id"]);
        $this->assertTrue(in_array($response["status"], ["completed", "failed", "processing"]));
        $this->assertTrue(in_array($response["authentication_result"], ["failed", "timeout", "error"]));
        
        // Validate optional failure details if present
        if (isset($response["failure_reason"])) {
            $this->assertNotEmpty($response["failure_reason"]);
        }
        
        if (isset($response["transaction_details"])) {
            $this->assertTrue(is_array($response["transaction_details"]));
        }
    }

    /**
     * @return CardAuthorizationRequest
     */
    public function getAuthorizationRequest()
    {
        $cardSimulation = new CardSimulation();
        $cardSimulation->id = $this->card["id"];
        $cardSimulation->expiry_month = $this->card["expiry_month"];
        $cardSimulation->expiry_year = $this->card["expiry_year"];

        $transactionSimulation = new TransactionSimulation();
        $transactionSimulation->type = TransactionType::$purchase;
        $transactionSimulation->amount = 100;
        $transactionSimulation->currency = Currency::$GBP;

        $authorizationRequest = new CardAuthorizationRequest();
        $authorizationRequest->card = $cardSimulation;
        $authorizationRequest->transaction = $transactionSimulation;
        return $authorizationRequest;
    }
}
