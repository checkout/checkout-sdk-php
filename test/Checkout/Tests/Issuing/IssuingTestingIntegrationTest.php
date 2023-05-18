<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Common\Currency;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardSimulation;
use Checkout\Issuing\Testing\TransactionSimulation;
use Checkout\Issuing\Testing\TransactionType;

class IssuingTestingIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $cardholder;
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
        $this->cardholder = $this->createCardholder();
        $this->card = $this->createCard($this->cardholder["id"], true);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateAuthorization()
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

        $simulationResponse = $this->issuingApi->getIssuingClient()->simulateAuthorization($authorizationRequest);

        $this->assertResponse(
            $simulationResponse,
            "id",
            "status"
        );
        $this->assertEquals("Authorized", $simulationResponse["status"]);
    }
}
