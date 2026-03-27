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
        $refundResponse = $this->issuingApi->getIssuingClient()->simulateRefund($simulationResponse["id"], $refundRequest);
        
        $this->assertNotNull($refundResponse);
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
