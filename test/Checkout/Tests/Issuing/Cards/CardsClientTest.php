<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Cards\Create\VirtualCardRequest;
use Checkout\Issuing\Cards\Credentials\CardCredentialsQuery;
use Checkout\Issuing\Cards\Suspend\SuspendCardRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class CardsClientTest extends UnitTestFixture
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
    public function shouldCreateCard()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "crd_12345"]);

        $request = new VirtualCardRequest();
        $response = $this->client->createCard($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crd_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCard()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => "crd_12345",
                "display_name" => "Test Card"
            ]);

        $response = $this->client->getCardDetails("crd_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crd_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardCredentials()
    {
        $this->apiClient
            ->method("query")
            ->willReturn([
                "number" => "4242424242424242",
                "cvc2" => "100"
            ]);

        $query = new CardCredentialsQuery();
        $response = $this->client->getCardCredentials("crd_12345", $query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("number", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSuspendCard()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "crd_12345"]);

        $request = new SuspendCardRequest();
        $response = $this->client->suspendCard("crd_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crd_12345", $response["id"]);
    }
}
