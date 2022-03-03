<?php

namespace Checkout\Tests\Tokens;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Sources\SepaSourceRequest;
use Checkout\Sources\SourcesClient;
use Checkout\Tests\UnitTestFixture;
use Checkout\Tokens\ApplePayTokenRequest;
use Checkout\Tokens\CardTokenRequest;
use Checkout\Tokens\GooglePayTokenRequest;
use Checkout\Tokens\TokensClient;

class TokensClientTest extends UnitTestFixture
{
    private TokensClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new TokensClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestCardToken(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestCardToken(new CardTokenRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRequestWalletToken(): void
    {

        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->requestWalletToken(new ApplePayTokenRequest());
        $this->assertNotNull($response);

        $response = $this->client->requestWalletToken(new GooglePayTokenRequest());
        $this->assertNotNull($response);
    }

}
