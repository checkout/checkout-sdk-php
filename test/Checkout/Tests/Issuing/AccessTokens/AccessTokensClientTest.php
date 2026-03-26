<?php

namespace Checkout\Tests\Issuing\AccessTokens;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\IssuingClient;
use Checkout\Issuing\CardholderAccessTokens\CardholderAccessTokenRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class AccessTokensClientTest extends UnitTestFixture
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
    public function shouldRequestCardholderAccessToken()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "access_token" => "2YotnFZFEjr1zCsicMWpAA",
                "token_type" => "bearer",
                "expires_in" => 3600,
                "scope" => "issuing:card-management-write issuing:card-management-read"
            ]);

        $request = $this->buildCardholderAccessTokenRequest();
        $response = $this->client->requestCardholderAccessToken($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("access_token", $response);
        $this->assertArrayHasKey("token_type", $response);
        $this->assertArrayHasKey("expires_in", $response);
        $this->assertEquals("2YotnFZFEjr1zCsicMWpAA", $response["access_token"]);
        $this->assertEquals("bearer", $response["token_type"]);
        $this->assertEquals(3600, $response["expires_in"]);
    }

    /**
     * @return CardholderAccessTokenRequest
     */
    private function buildCardholderAccessTokenRequest()
    {
        $request = new CardholderAccessTokenRequest();
        $request->grant_type = "client_credentials";
        $request->client_id = "client_id";
        $request->client_secret = "client_secret";
        $request->cardholder_id = "crh_abcdefghijklmnopqrstuvwxyz12";
        $request->single_use = true;

        return $request;
    }
}
