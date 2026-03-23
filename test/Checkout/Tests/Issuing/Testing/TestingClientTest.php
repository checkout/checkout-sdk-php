<?php

namespace Checkout\Tests\Issuing\Testing;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\Testing\CardAuthorizationRequest;
use Checkout\Issuing\Testing\CardClearingAuthorizationRequest;
use Checkout\Issuing\Testing\CardIncrementAuthorizationRequest;
use Checkout\Issuing\Testing\CardReversalAuthorizationRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class TestingClientTest extends UnitTestFixture
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
    public function shouldSimulateAuthorization()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Authorized"
            ]);

        $request = new CardAuthorizationRequest();
        $response = $this->client->simulateAuthorization($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateIncrement()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Authorized"
            ]);

        $request = new CardIncrementAuthorizationRequest();
        $response = $this->client->simulateIncrementingAuthorization("auth_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateClearing()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Cleared"
            ]);

        $request = new CardClearingAuthorizationRequest();
        $response = $this->client->simulateClearing("auth_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSimulateReversal()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "id" => "sim_12345",
                "status" => "Reversed"
            ]);

        $request = new CardReversalAuthorizationRequest();
        $response = $this->client->simulateReversal("auth_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("sim_12345", $response["id"]);
    }
}
