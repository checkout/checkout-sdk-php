<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cards\Credentials\CardCredentialsQuery;
use Checkout\Issuing\Cards\Enrollment\PasswordThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Enrollment\SecurityPair;
use Checkout\Issuing\Cards\Enrollment\UpdateThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Revoke\RevokeReason;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendReason;

class IssuingCardsIntegrationTest extends AbstractIssuingIntegrationTest
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
        $this->card = $this->createCard($this->cardholder["id"]);
    }

    /**
     * @test
     */
    public function shouldCreateCard()
    {
        $card = $this->card;

        $this->assertResponse(
            $card,
            "id",
            "display_name",
            "last_four",
            "expiry_month",
            "expiry_year",
            "billing_currency",
            "issuing_country",
            "reference"
        );
        $this->assertEquals("JOHN KENNEDY", $card["display_name"]);
        $this->assertEquals("X-123456-N11", $card["reference"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardDetails()
    {
        $cardResponse = $this->issuingApi->getIssuingClient()->getCardDetails($this->card["id"]);

        $this->assertResponse(
            $cardResponse,
            "id",
            "cardholder_id",
            "card_product_id",
            "display_name",
            "last_four",
            "expiry_month",
            "expiry_year",
            "billing_currency",
            "issuing_country",
            "reference",
            "status",
            "type"
        );
        $this->assertEquals($this->card["id"], $cardResponse["id"]);
        $this->assertEquals($this->cardholder["id"], $cardResponse["cardholder_id"]);
        $this->assertEquals("pro_3fn6pv2ikshurn36dbd3iysyha", $cardResponse["card_product_id"]);
        $this->assertEquals("X-123456-N11", $cardResponse["reference"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldEnrollCardIntoThreeDS()
    {
        $enrollRequest = new PasswordThreeDSEnrollmentRequest();
        $enrollRequest->password = $this->getPassword();
        $enrollRequest->locale = "en-US";
        $enrollRequest->phone_number = $this->getPhone();

        $enrollmentResponse = $this->issuingApi->getIssuingClient()->enrollThreeDS($this->card["id"], $enrollRequest);

        $this->assertEquals(202, $enrollmentResponse["http_metadata"]->getStatusCode());
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateThreeDSEnrollment()
    {
        $securityPair = new SecurityPair();
        $securityPair->question = "Who are you?";
        $securityPair->answer = "Bond. James Bond.";

        $enrollRequest = new UpdateThreeDSEnrollmentRequest();
        $enrollRequest->password = $this->getPassword();
        $enrollRequest->security_pair = $securityPair;
        $enrollRequest->locale = "en-US";
        $enrollRequest->phone_number = $this->getPhone();

        $updateResponse = $this->issuingApi->getIssuingClient()->updateThreeDSEnrollment(
            $this->card["id"],
            $enrollRequest
        );

        $this->assertEquals(202, $updateResponse["http_metadata"]->getStatusCode());
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetThreeDSDetails()
    {
        $threeDSResponse = $this->issuingApi->getIssuingClient()->getCardThreeDSDetails($this->card["id"]);

        $this->assertResponse(
            $threeDSResponse,
            "locale",
            "phone_number"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldActivateCard()
    {
        $activateResponse = $this->issuingApi->getIssuingClient()->activateCard($this->card["id"]);

        $this->assertEquals(200, $activateResponse["http_metadata"]->getStatusCode());

        $cardResponse = $this->issuingApi->getIssuingClient()->getCardDetails($this->card["id"]);

        $this->assertEquals("active", $cardResponse["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardCredentials()
    {
        $queryRequest = new CardCredentialsQuery();
        $queryRequest->credentials = "number, cvc2";

        $queryResponse = $this->issuingApi->getIssuingClient()->getCardCredentials($this->card["id"], $queryRequest);

        $this->assertResponse(
            $queryResponse,
            "number",
            "cvc2"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRevokeCard()
    {
        $card = $this->createCard($this->cardholder["id"], true);

        $revokeRequest = new RevokeCardRequest();
        $revokeRequest->reason = RevokeReason::$reported_stolen;

        $revokeResponse = $this->issuingApi->getIssuingClient()->revokeCard($card["id"], $revokeRequest);

        $this->assertEquals(200, $revokeResponse["http_metadata"]->getStatusCode());

        $cardResponse = $this->issuingApi->getIssuingClient()->getCardDetails($card["id"]);

        $this->assertEquals("revoked", $cardResponse["status"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSuspendCard()
    {
        $card = $this->createCard($this->cardholder["id"], true);

        $suspendRequest = new SuspendCardRequest();
        $suspendRequest->reason = SuspendReason::$suspected_stolen;

        $suspendResponse = $this->issuingApi->getIssuingClient()->suspendCard($card["id"], $suspendRequest);

        $this->assertEquals(200, $suspendResponse["http_metadata"]->getStatusCode());

        $cardResponse = $this->issuingApi->getIssuingClient()->getCardDetails($card["id"]);

        $this->assertEquals("suspended", $cardResponse["status"]);
    }
}
