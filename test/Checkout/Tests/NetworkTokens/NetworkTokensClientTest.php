<?php

namespace Checkout\Tests\NetworkTokens;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\NetworkTokens\NetworkTokensClient;
use Checkout\NetworkTokens\Requests\ProvisionNetworkTokenRequest;
use Checkout\NetworkTokens\Requests\RequestCryptogramRequest;
use Checkout\NetworkTokens\Requests\DeleteNetworkTokenRequest;
use Checkout\NetworkTokens\Entities\IdSource;
use Checkout\NetworkTokens\Entities\CardSource;
use Checkout\NetworkTokens\Entities\TransactionType;
use Checkout\NetworkTokens\Entities\InitiatedByType;
use Checkout\NetworkTokens\Entities\ReasonType;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class NetworkTokensClientTest extends UnitTestFixture
{
    /**
     * @var NetworkTokensClient
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
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new NetworkTokensClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldProvisionANetworkToken()
    {
        $expectedResponse = $this->buildExpectedProvisionNetworkTokenResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildProvisionNetworkTokenRequest();
        $response = $this->client->provisionNetworkToken($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("card", $response);
        $this->assertArrayHasKey("network_token", $response);
        $this->assertArrayHasKey("token_requestor_id", $response);
        $this->assertEquals($expectedResponse["network_token"]["id"], $response["network_token"]["id"]);
        $this->assertEquals($expectedResponse["card"]["last4"], $response["card"]["last4"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetNetworkToken()
    {
        $networkTokenId = "nt_xgu3isllqfyu7ktpk5z2yxbwna";
        $expectedResponse = $this->buildExpectedNetworkTokenResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getNetworkToken($networkTokenId);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("card", $response);
        $this->assertArrayHasKey("network_token", $response);
        $this->assertEquals($networkTokenId, $response["network_token"]["id"]);
        $this->assertEquals("active", $response["network_token"]["state"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestACryptogram()
    {
        $networkTokenId = "nt_xgu3isllqfyu7ktpk5z2yxbwna";
        $expectedResponse = $this->buildExpectedCryptogramResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildRequestCryptogramRequest();
        $response = $this->client->requestCryptogram($networkTokenId, $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("cryptogram", $response);
        $this->assertArrayHasKey("eci", $response);
        $this->assertNotEmpty($response["cryptogram"]);
        $this->assertEquals($expectedResponse["cryptogram"], $response["cryptogram"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteNetworkToken()
    {
        $networkTokenId = "nt_xgu3isllqfyu7ktpk5z2yxbwna";
        $expectedResponse = [];

        $this->apiClient
            ->method("patch")
            ->willReturn($expectedResponse);

        $request = $this->buildDeleteNetworkTokenRequest();
        $response = $this->client->deleteNetworkToken($networkTokenId, $request);

        $this->assertNotNull($response);
        $this->assertEquals([], $response);
    }

    // Builder methods for DRY principle

    private function buildProvisionNetworkTokenRequest()
    {
        $source = new IdSource();
        $source->id = "src_wmlfc3zyhqzehihu7giusaaawu";

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

    // Response builders for expected responses

    private function buildExpectedProvisionNetworkTokenResponse()
    {
        return [
            "card" => [
                "last4" => "6378",
                "expiry_month" => "5",
                "expiry_year" => "2025"
            ],
            "network_token" => [
                "id" => "nt_xgu3isllqfyu7ktpk5z2yxbwna",
                "state" => "active",
                "number" => "5436424242424242",
                "expiry_month" => "5",
                "expiry_year" => "2025",
                "type" => "vts",
                "payment_account_reference" => "5001a9f027e5629d11e3949a0800a",
                "created_on" => "2020-02-11T15:57:32.435+00:00",
                "modified_on" => "2020-02-11T15:57:32.435+00:00"
            ],
            "token_requestor_id" => "1234567890",
            "token_scheme_id" => "scheme_visa_001",
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/network-tokens/nt_xgu3isllqfyu7ktpk5z2yxbwna"
                ],
                "cryptogram" => [
                    "href" => "https://api.checkout.com/network-tokens/nt_xgu3isllqfyu7ktpk5z2yxbwna/cryptograms"
                ]
            ]
        ];
    }

    private function buildExpectedNetworkTokenResponse()
    {
        return [
            "card" => [
                "last4" => "6378",
                "expiry_month" => "5",
                "expiry_year" => "2025"
            ],
            "network_token" => [
                "id" => "nt_xgu3isllqfyu7ktpk5z2yxbwna",
                "state" => "active",
                "number" => "5436424242424242",
                "expiry_month" => "5",
                "expiry_year" => "2025",
                "type" => "vts",
                "payment_account_reference" => "5001a9f027e5629d11e3949a0800a",
                "created_on" => "2020-02-11T15:57:32.435+00:00",
                "modified_on" => "2020-02-11T15:57:32.435+00:00"
            ],
            "token_requestor_id" => "1234567890",
            "token_scheme_id" => "scheme_visa_001"
        ];
    }

    private function buildExpectedCryptogramResponse()
    {
        return [
            "cryptogram" => "AhJ3hnYAoAbVz5zg1e17MAACAAA=",
            "eci" => "7",
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/network-tokens/nt_xgu3isllqfyu7ktpk5z2yxbwna/cryptograms"
                ],
                "network-token" => [
                    "href" => "https://api.checkout.com/network-tokens/nt_xgu3isllqfyu7ktpk5z2yxbwna"
                ]
            ]
        ];
    }
}
