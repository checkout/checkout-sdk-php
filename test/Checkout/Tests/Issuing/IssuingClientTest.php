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
use Checkout\Issuing\IssuingClient;
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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

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
            ->willReturn("foo");

        $response = $this->client->suspendCard("card_id", new SuspendCardRequest());
        $this->assertNotNull($response);
    }
}
