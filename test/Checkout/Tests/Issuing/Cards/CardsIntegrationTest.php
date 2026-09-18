<?php

namespace Checkout\Tests\Issuing\Cards;

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
use Checkout\Issuing\Cards\Create\CardLifetime;
use Checkout\Issuing\Cards\Create\LifetimeUnit;
use Checkout\Issuing\Cards\Create\VirtualCardRequest;
use Checkout\Issuing\Cards\Update\CardUpdateHeaders;
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\Issuing\Cards\Renew\RenewCardRequest;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class CardsIntegrationTest extends AbstractIssuingIntegrationTest
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

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardDetails()
    {
        $card = $this->createCard($this->cardholder["id"]);

        $updateRequest = new UpdateCardRequest();
        $updateRequest->reference = "UPDATED-REF-123";
        
        $updateResponse = $this->issuingApi->getIssuingClient()->updateCardDetails($card["id"], $updateRequest);

        $this->assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());
        
        $this->assertResponse(
            $updateResponse,
            "id",
            "last_modified_date"
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateCardWithScheduledActivationDate()
    {
        $lifetime = new CardLifetime();
        $lifetime->unit = LifetimeUnit::$months;
        $lifetime->value = 6;

        $cardRequest = new VirtualCardRequest();
        $cardRequest->cardholder_id = $this->cardholder["id"];
        $cardRequest->card_product_id = "pro_3fn6pv2ikshurn36dbd3iysyha";
        $cardRequest->lifetime = $lifetime;
        $cardRequest->reference = "X-123456-N11";
        $cardRequest->display_name = "John Kennedy";
        $cardRequest->is_single_use = false;
        $cardRequest->scheduled_activation_date = $this->nextRoundHour();

        $cardResponse = $this->issuingApi->getIssuingClient()->createCard($cardRequest);

        $this->assertResponse($cardResponse, "id");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardScheduledActivationDate()
    {
        $card = $this->createCard($this->cardholder["id"]);

        $updateRequest = new UpdateCardRequest();
        $updateRequest->scheduled_activation_date = $this->nextRoundHour();

        $updateResponse = $this->issuingApi->getIssuingClient()->updateCardDetails($card["id"], $updateRequest);

        $this->assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());
        $this->assertResponse($updateResponse, "last_modified_date");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardRevocationDate()
    {
        $card = $this->createCard($this->cardholder["id"]);

        $updateRequest = new UpdateCardRequest();
        $updateRequest->revocation_date = gmdate("Y-m-d", strtotime("+1 year"));

        $updateResponse = $this->issuingApi->getIssuingClient()->updateCardDetails($card["id"], $updateRequest);

        $this->assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());
        $this->assertResponse($updateResponse, "last_modified_date");
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardDetailsReturningEncryptedCvv()
    {
        $card = $this->createCard($this->cardholder["id"], true);

        $updateRequest = new UpdateCardRequest();
        $updateRequest->reference = "UPDATED-REF-123";

        $headers = new CardUpdateHeaders();
        $headers->return_encrypted_cvv = "true";
        $headers->encryption_key = $this->getEncryptionKey();

        $updateResponse = $this->issuingApi->getIssuingClient()
            ->updateCardDetails($card["id"], $updateRequest, $headers);

        $this->assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());
        $this->assertResponse($updateResponse, "last_modified_date", "encrypted_cvv");
        $this->assertNotEmpty($updateResponse["encrypted_cvv"]);
    }

    /**
     * The next round hour in UTC, which is the earliest value the API accepts for a scheduled
     * activation date carrying a time.
     *
     * @return string
     */
    private function nextRoundHour(): string
    {
        return gmdate("Y-m-d\\TH:00\\Z", strtotime("+2 hours"));
    }

    /**
     * The RSA public key used to encrypt returned credentials, with the PEM headers and newlines
     * removed. Supplied by the environment because it pairs with a private key the test cannot
     * hold.
     *
     * @return string
     */
    private function getEncryptionKey(): string
    {
        return getenv("CHECKOUT_ISSUING_ENCRYPTION_KEY") ?: "";
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRenewCard()
    {
        $card = $this->createCard($this->cardholder["id"]);

        $renewRequest = new RenewCardRequest();
        $renewRequest->reference = "RENEW-REF-123";
        
        $renewResponse = $this->issuingApi->getIssuingClient()->renewCard($card["id"], $renewRequest);

        $this->assertEquals(201, $renewResponse["http_metadata"]->getStatusCode());
        
        $this->assertResponse(
            $renewResponse,
            "id",
            "display_name",
            "last_four",
            "expiry_month",
            "expiry_year"
        );
    }

}
