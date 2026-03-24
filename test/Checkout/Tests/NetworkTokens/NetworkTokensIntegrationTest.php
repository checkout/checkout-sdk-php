<?php

namespace Checkout\Tests\NetworkTokens;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\NetworkTokens\Requests\ProvisionNetworkTokenRequest;
use Checkout\NetworkTokens\Requests\RequestCryptogramRequest;
use Checkout\NetworkTokens\Requests\DeleteNetworkTokenRequest;
use Checkout\NetworkTokens\Entities\IdSource;
use Checkout\NetworkTokens\Entities\CardSource;
use Checkout\NetworkTokens\Entities\TransactionType;
use Checkout\NetworkTokens\Entities\InitiatedByType;
use Checkout\NetworkTokens\Entities\ReasonType;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class NetworkTokensIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldProvisionANetworkToken()
    {
        $this->markTestSkipped("use on demand");

        $request = $this->buildProvisionNetworkTokenRequest();

        $response = $this->checkoutApi->getNetworkTokensClient()->provisionNetworkToken($request);

        $this->validateProvisionNetworkTokenResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetNetworkToken()
    {
        $this->markTestSkipped("use on demand");

        $networkTokenId = "nt_xgu3isllqfyu7ktpk5z2yxbwna";

        $response = $this->checkoutApi->getNetworkTokensClient()->getNetworkToken($networkTokenId);

        $this->validateGetNetworkTokenResponse($response, $networkTokenId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestACryptogram()
    {
        $this->markTestSkipped("use on demand");

        $networkTokenId = "nt_xgu3isllqfyu7ktpk5z2yxbwna";
        $request = $this->buildRequestCryptogramRequest();

        $response = $this->checkoutApi->getNetworkTokensClient()->requestCryptogram($networkTokenId, $request);

        $this->validateRequestCryptogramResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteNetworkToken()
    {
        $this->markTestSkipped("use on demand");

        $networkTokenId = "nt_xgu3isllqfyu7ktpk5z2yxbwna";
        $request = $this->buildDeleteNetworkTokenRequest();

        $response = $this->checkoutApi->getNetworkTokensClient()->deleteNetworkToken($networkTokenId, $request);

        $this->validateDeleteNetworkTokenResponse($response);
    }

    // Builder methods for DRY principle

    private function buildProvisionNetworkTokenRequest()
    {
        $source = new IdSource();
        $source->id = "src_wmlfc3zyhqzehihu7ktpk5z2yxbwna";

        $request = new ProvisionNetworkTokenRequest();
        $request->source = $source;

        return $request;
    }

    private function buildProvisionNetworkTokenWithCardRequest()
    {
        $source = new CardSource();
        $source->number = "4539467987109256";
        $source->expiry_month = "10";
        $source->expiry_year = "2027";

        $request = new ProvisionNetworkTokenRequest();
        $request->source = $source;

        return $request;
    }

    private function buildRequestCryptogramRequest()
    {
        $request = new RequestCryptogramRequest();
        $request->transaction_type = TransactionType::$ecom;

        return $request;
    }

    private function buildDeleteNetworkTokenRequest()
    {
        $request = new DeleteNetworkTokenRequest();
        $request->initiated_by = InitiatedByType::$token_requestor;
        $request->reason = ReasonType::$other;

        return $request;
    }

    // Validation methods for DRY principle

    private function validateProvisionNetworkTokenResponse($response, $request)
    {
        $this->assertResponse(
            $response,
            "card",
            "network_token",
            "token_requestor_id",
            "token_scheme_id",
            "_links"
        );

        // Validate card details
        $this->assertArrayHasKey("last4", $response["card"]);
        $this->assertArrayHasKey("expiry_month", $response["card"]);
        $this->assertArrayHasKey("expiry_year", $response["card"]);

        // Validate network token details
        $this->assertArrayHasKey("id", $response["network_token"]);
        $this->assertArrayHasKey("state", $response["network_token"]);
        $this->assertArrayHasKey("type", $response["network_token"]);
        
        // Validate state is valid
        $this->assertContains($response["network_token"]["state"], ["active", "suspended", "inactive"]);
        
        // Validate type is valid
        $this->assertContains($response["network_token"]["type"], ["vts", "mdes"]);
    }

    private function validateGetNetworkTokenResponse($response, $networkTokenId)
    {
        $this->assertResponse(
            $response,
            "card",
            "network_token",
            "token_requestor_id",
            "token_scheme_id"
        );

        // Validate network token ID matches
        $this->assertEquals($networkTokenId, $response["network_token"]["id"]);

        // Validate card details
        $this->assertArrayHasKey("last4", $response["card"]);
        $this->assertArrayHasKey("expiry_month", $response["card"]);
        $this->assertArrayHasKey("expiry_year", $response["card"]);

        // Validate network token details
        $this->assertArrayHasKey("state", $response["network_token"]);
        $this->assertArrayHasKey("type", $response["network_token"]);
        $this->assertArrayHasKey("created_on", $response["network_token"]);
        $this->assertArrayHasKey("modified_on", $response["network_token"]);

        // Validate state is valid
        $validStates = ["active", "suspended", "inactive", "declined", "requested"];
        $this->assertContains($response["network_token"]["state"], $validStates);
    }

    private function validateRequestCryptogramResponse($response, $request)
    {
        $this->assertResponse(
            $response,
            "cryptogram",
            "_links"
        );

        // Validate cryptogram is not empty
        $this->assertNotEmpty($response["cryptogram"]);

        // Validate ECI if present
        if (isset($response["eci"])) {
            $this->assertNotEmpty($response["eci"]);
        }

        // Validate links
        $this->assertArrayHasKey("self", $response["_links"]);
        $this->assertArrayHasKey("network-token", $response["_links"]);
    }

    private function validateDeleteNetworkTokenResponse($response)
    {
        // Delete operation typically returns empty response for 204 status
        $this->assertNotNull($response);
        $this->assertEquals([], $response);
    }

    /**
     * Full integration test flow - provision, get, request cryptogram, delete
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompleteNetworkTokenFlow()
    {
        $this->markTestSkipped("use on demand - requires valid test environment");

        // Step 1: Provision a network token
        $provisionRequest = $this->buildProvisionNetworkTokenRequest();
        $provisionResponse = $this->checkoutApi->getNetworkTokensClient()->provisionNetworkToken($provisionRequest);
        
        $this->validateProvisionNetworkTokenResponse($provisionResponse, $provisionRequest);
        $networkTokenId = $provisionResponse["network_token"]["id"];

        // Step 2: Get the network token details
        $getResponse = $this->checkoutApi->getNetworkTokensClient()->getNetworkToken($networkTokenId);
        $this->validateGetNetworkTokenResponse($getResponse, $networkTokenId);

        // Step 3: Request a cryptogram
        $cryptogramRequest = $this->buildRequestCryptogramRequest();
        $cryptogramResponse = $this->checkoutApi->getNetworkTokensClient()->requestCryptogram($networkTokenId, $cryptogramRequest);
        $this->validateRequestCryptogramResponse($cryptogramResponse, $cryptogramRequest);

        // Step 4: Delete the network token
        $deleteRequest = $this->buildDeleteNetworkTokenRequest();
        $deleteResponse = $this->checkoutApi->getNetworkTokensClient()->deleteNetworkToken($networkTokenId, $deleteRequest);
        $this->validateDeleteNetworkTokenResponse($deleteResponse);
    }
}
