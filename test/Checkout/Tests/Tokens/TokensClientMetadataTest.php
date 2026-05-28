<?php

namespace Checkout\Tests\Tokens;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;
use Checkout\Tokens\TokensClient;

class TokensClientMetadataTest extends UnitTestFixture
{
    /**
     * @var TokensClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new TokensClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetTokenMetadata()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "token" => "tok_test123",
                "type" => "card",
                "scheme" => "Visa",
                "last4" => "4242",
                "issuer_country" => "US"
            ]);

        $response = $this->client->getTokenMetadata("tok_test123");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetTokenMetadataWithTokenId()
    {
        $expectedTokenId = "tok_test456";
        
        $this->apiClient
            ->method("get")
            ->willReturn([
                "token" => $expectedTokenId,
                "type" => "card",
                "bin" => "453255",
                "card_type" => "Credit"
            ]);

        $response = $this->client->getTokenMetadata($expectedTokenId);
        $this->assertNotNull($response);
    }
}
