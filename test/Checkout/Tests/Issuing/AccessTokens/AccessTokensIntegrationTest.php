<?php

namespace Checkout\Tests\Issuing\AccessTokens;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\CardholderAccessTokens\CardholderAccessTokenRequest;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class AccessTokensIntegrationTest extends AbstractIssuingIntegrationTest
{
    /**
     * @test
     * @skip This test requires issuing access key credentials and a valid cardholder id
     */
    public function shouldRequestCardholderAccessToken()
    {
        $cardholder = $this->createCardholder();
        $request = $this->buildCardholderAccessTokenRequest($cardholder["id"]);

        $response = $this->issuingApi->getIssuingClient()->requestCardholderAccessToken($request);

        $this->assertResponse($response, "access_token", "token_type", "expires_in");
        $this->assertNotNull($response["access_token"]);
        $this->assertNotNull($response["token_type"]);
        $this->assertNotNull($response["expires_in"]);
    }

    /**
     * @param string $cardholderId
     * @return CardholderAccessTokenRequest
     */
    private function buildCardholderAccessTokenRequest($cardholderId)
    {
        $request = new CardholderAccessTokenRequest();
        $request->grant_type = "client_credentials";
        $request->client_id = getenv("CHECKOUT_DEFAULT_OAUTH_ISSUING_CLIENT_ID");
        $request->client_secret = getenv("CHECKOUT_DEFAULT_OAUTH_ISSUING_CLIENT_SECRET");
        $request->cardholder_id = $cardholderId;
        $request->single_use = true;

        return $request;
    }
}
