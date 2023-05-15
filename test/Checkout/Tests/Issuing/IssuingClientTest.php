<?php

namespace Checkout\Tests\Issuing;

use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cardholders\CardholderRequest;
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
}
