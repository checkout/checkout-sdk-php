<?php

namespace Checkout\Tests\Issuing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cardholders\CardholderRequest;
use Checkout\Issuing\Cards\Create\PhysicalCardRequest;
use Checkout\Issuing\Cards\Credentials\CardCredentialsQuery;
use Checkout\Issuing\Cards\Enrollment\PasswordThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Enrollment\UpdateThreeDSEnrollmentRequest;
use Checkout\Issuing\Cards\Revoke\RevokeCardRequest;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\Issuing\Cards\Renew\RenewCardRequest;
use Checkout\Issuing\Cards\ScheduleRevocation\ScheduleRevocationRequest;
use Checkout\Issuing\Controls\Create\VelocityCardControlRequest;
use Checkout\Issuing\Controls\Query\CardControlsQuery;
use Checkout\Issuing\Controls\Update\UpdateCardControlRequest;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\Issuing\Testing\SimulateRefundRequest;
use Checkout\Issuing\CardholderAccessTokens\CardholderAccessTokenRequest;
use Checkout\Issuing\Cardholders\UpdateCardholderRequest;
use Checkout\Issuing\Transactions\Requests\TransactionsQuery;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class IssuingClientTest extends UnitTestFixture
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
    public function shouldCreateCardholder()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->createCardholder(new CardholderRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardholderDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCardholder("cardholder_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardholderCards()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCardholderCards("cardholder_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateCard()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->createCard(new PhysicalCardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCardDetails("card_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldEnrollThreeDS()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->enrollThreeDS("card_id", new PasswordThreeDSEnrollmentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateThreeDSEnrollment()
    {

        $this->apiClient
            ->method("patch")
            ->willReturn(["foo"]);

        $response = $this->client->updateThreeDSEnrollment("card_id", new UpdateThreeDSEnrollmentRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardThreeDSDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCardThreeDSDetails("card_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldActivateCard()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->activateCard("card_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardCredentials()
    {

        $this->apiClient
            ->method("query")
            ->willReturn(["foo"]);

        $response = $this->client->getCardCredentials("card_id", new CardCredentialsQuery());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRevokeCard()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->revokeCard("card_id", new RevokeCardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSuspendCard()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->suspendCard("card_id", new SuspendCardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateControl()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->createControl(new VelocityCardControlRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardControls()
    {

        $this->apiClient
            ->method("query")
            ->willReturn(["foo"]);

        $response = $this->client->getCardControls(new CardControlsQuery());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetControlDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCardControlDetails("control_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardControl()
    {

        $this->apiClient
            ->method("put")
            ->willReturn(["foo"]);

        $response = $this->client->updateCardControl("control_id", new UpdateCardControlRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRemoveControl()
    {

        $this->apiClient
            ->method("delete")
            ->willReturn(["foo"]);

        $response = $this->client->removeCardControl("control_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateAuthorization()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->simulateAuthorization(new CardAuthorizationRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateIncrementingAuthorization()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->simulateIncrementingAuthorization(
            "authorization_id",
            new CardIncrementAuthorizationRequest()
        );
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateClearing()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->simulateClearing(
            "authorization_id",
            new CardClearingAuthorizationRequest()
        );
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateReversal()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->simulateReversal(
            "authorization_id",
            new CardReversalAuthorizationRequest()
        );
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetTransactions()
    {
        $this->apiClient
            ->method("query")
            ->willReturn(["foo"]);

        $response = $this->client->getListTransactions(new TransactionsQuery());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetTransactionsWithEmptyQuery()
    {
        $this->apiClient
            ->method("query")
            ->willReturn(["data" => []]);

        $query = new TransactionsQuery();
        $response = $this->client->getListTransactions($query);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetTransactionDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getSingleTransaction("transaction_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardDetails()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn(["foo"]);

        $response = $this->client->updateCardDetails("card_id", new UpdateCardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRenewCard()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->renewCard("card_id", new RenewCardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldScheduleCardRevocation()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->scheduleCardRevocation("card_id", new ScheduleRevocationRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteScheduledCardRevocation()
    {
        $this->apiClient
            ->method("delete")
            ->willReturn(["foo"]);

        $response = $this->client->deleteScheduledCardRevocation("card_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateRefund()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->simulateRefund("authorization_id", new SimulateRefundRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestCardholderAccessToken()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["access_token" => "token123", "token_type" => "bearer", "expires_in" => 3600]);

        $response = $this->client->requestCardholderAccessToken(new CardholderAccessTokenRequest());
        $this->assertNotNull($response);
        $this->assertArrayHasKey("access_token", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardholder()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn(["last_modified_date" => "2019-09-10T10:11:12Z"]);

        $response = $this->client->updateCardholder("cardholder_id", new UpdateCardholderRequest());
        $this->assertNotNull($response);
    }
}
