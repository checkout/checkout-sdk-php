<?php

namespace Checkout\Tests\Issuing\Cardholders;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cardholders\CardholderType;
use Checkout\Issuing\Cardholders\UpdateCardholderRequest;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class CardholdersIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $cardholder;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function beforeAll()
    {
        $this->before();
        $this->cardholder = $this->createCardholder();
    }

    /**
     * @test
     */
    public function shouldCreateCardholder()
    {
        $cardholder = $this->cardholder;

        $this->assertResponse(
            $cardholder,
            "id",
            "type",
            "status",
            "reference"
        );
        $this->assertEquals(CardholderType::$individual, $cardholder["type"]);
        $this->assertEquals("active", $cardholder["status"]);
        $this->assertEquals("X-123456-N11", $cardholder["reference"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardholder()
    {
        $cardholderResponse = $this->issuingApi->getIssuingClient()->getCardholder($this->cardholder["id"]);

        $this->assertResponse(
            $cardholderResponse,
            "id",
            "type",
            "status",
            "reference"
        );
        $this->assertEquals(CardholderType::$individual, $cardholderResponse["type"]);
        $this->assertEquals("Active", $cardholderResponse["status"]);
        $this->assertEquals("X-123456-N11", $cardholderResponse["reference"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardholderCards()
    {
        $cardholderCardsResponse = $this->issuingApi->getIssuingClient()->getCardholderCards($this->cardholder["id"]);

        foreach ($cardholderCardsResponse["cards"] as $card) {
            $this->assertEquals($this->cardholder["id"], $card["id"]);
            $this->assertEquals("cli_p6jeowdtuxku3azxgt2qa7kq7a", $card["client_id"]);
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardholder()
    {
        $updateRequest = $this->buildUpdateCardholderRequest();
        
        $updateResponse = $this->issuingApi->getIssuingClient()->updateCardholder($this->cardholder["id"], $updateRequest);

        $this->assertResponse($updateResponse, "last_modified_date", "_links");
        $this->assertNotNull($updateResponse["last_modified_date"]);
        
        // Verify the update by fetching cardholder details
        $updatedCardholder = $this->issuingApi->getIssuingClient()->getCardholder($this->cardholder["id"]);
        $this->assertEquals("UpdatedName", $updatedCardholder["first_name"]);
    }

    /**
     * @return UpdateCardholderRequest
     */
    private function buildUpdateCardholderRequest()
    {
        $address = $this->getAddress();
        
        $request = new UpdateCardholderRequest();
        $request->first_name = "UpdatedName";
        $request->email = "updated.john.kennedy@myemaildomain.com";
        $request->billing_address = $address;

        return $request;
    }
}
