<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cardholders\CardholderType;

class IssuingCardholdersIntegrationTest extends AbstractIssuingIntegrationTest
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
}
