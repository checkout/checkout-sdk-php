<?php

namespace Checkout\Tests\Issuing\Cardholders;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Cardholders\CardholderRequest;
use Checkout\Issuing\Cardholders\UpdateCardholderRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class CardholdersClientTest extends UnitTestFixture
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
            ->willReturn(["id" => "crh_12345"]);

        $request = new CardholderRequest();
        $response = $this->client->createCardholder($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crh_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardholder()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => "crh_12345",
                "type" => "individual",
                "status" => "active"
            ]);

        $response = $this->client->getCardholder("crh_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("crh_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCardholderCards()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "cards" => [
                    ["id" => "crd_12345", "cardholder_id" => "crh_12345"]
                ]
            ]);

        $response = $this->client->getCardholderCards("crh_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("cards", $response);
        $this->assertCount(1, $response["cards"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardholder()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn([
                "last_modified_date" => "2019-09-10T10:11:12Z",
                "_links" => [
                    "self" => [
                        "href" => "https://api.sandbox.checkout.com/issuing/cardholders/crh_12345"
                    ]
                ]
            ]);

        $request = $this->buildUpdateCardholderRequest();
        $response = $this->client->updateCardholder("crh_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("last_modified_date", $response);
    }

    /**
     * @return UpdateCardholderRequest
     */
    private function buildUpdateCardholderRequest()
    {
        $request = new UpdateCardholderRequest();
        $request->first_name = "UpdatedName";
        $request->email = "updated.email@example.com";

        return $request;
    }
}
