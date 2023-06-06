<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\Issuing\Testing\CardSimulation;
use Checkout\Issuing\Testing\TransactionSimulation;
use Checkout\Issuing\Testing\TransactionType;

class IssuingTestingIntegrationTest extends AbstractIssuingIntegrationTest
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

        $cardIncrementAuthorizationRequest = new CardIncrementAuthorizationRequest();
        $cardIncrementAuthorizationRequest->amount = 1;

        $cardIncrementAuthorizationResponse = $this->issuingApi->getIssuingClient()->
        simulateIncrementingAuthorization(
            $authorizationRequest["id"],
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

        $cardClearingAuthorizationRequest = new CardClearingAuthorizationRequest();
        $cardClearingAuthorizationRequest->amount = 1;

        $cardClearingAuthorizationResponse = $this->issuingApi->getIssuingClient()->
        simulateClearing(
            $authorizationRequest["id"],
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

        $cardReversalAuthorizationRequest = new CardReversalAuthorizationRequest();
        $cardReversalAuthorizationRequest->amount = 1;

        $cardReversalAuthorizationResponse = $this->issuingApi->getIssuingClient()->
        simulateReversal(
            $authorizationRequest["id"],
            $cardReversalAuthorizationRequest
        );

        $this->assertResponse(
            $cardReversalAuthorizationResponse,
            "status"
        );
        $this->assertEquals("Reversed", $cardReversalAuthorizationResponse["status"]);
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
