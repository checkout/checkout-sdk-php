<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\ApiClient;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\Cards\Update\CardUpdateHeaders;
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\Tests\UnitTestFixture;
use ReflectionMethod;

class CardUpdateHeadersTest extends UnitTestFixture
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
     */
    public function shouldMapBothPropertiesToTheirExactHeaderNames()
    {
        $mappings = (new CardUpdateHeaders())->getHeaderMappings();

        $this->assertSame(
            [
                "return_encrypted_cvv" => "return-encrypted-cvv",
                "encryption_key" => "Encryption-Key"
            ],
            $mappings
        );
    }

    /**
     * The header names are case sensitive and neither matches the default snake_case to
     * Pascal-Case conversion, which would emit Return-Encrypted-Cvv and Encryption-Key.
     *
     * @test
     * @throws CheckoutAuthorizationException
     */
    public function shouldBuildTheExactHeaderNamesOnTheWire()
    {
        $headers = new CardUpdateHeaders();
        $headers->return_encrypted_cvv = "true";
        $headers->encryption_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A";

        $built = $this->buildRequestHeaders($headers);

        $this->assertArrayHasKey("return-encrypted-cvv", $built);
        $this->assertArrayNotHasKey("Return-Encrypted-Cvv", $built);
        $this->assertSame("true", $built["return-encrypted-cvv"]);
        $this->assertArrayHasKey("Encryption-Key", $built);
        $this->assertSame("MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A", $built["Encryption-Key"]);
    }

    /**
     * @test
     * @throws CheckoutAuthorizationException
     */
    public function shouldOmitUnsetHeaders()
    {
        $built = $this->buildRequestHeaders(new CardUpdateHeaders());

        $this->assertArrayNotHasKey("return-encrypted-cvv", $built);
        $this->assertArrayNotHasKey("Encryption-Key", $built);
    }

    /**
     * @test
     * @throws CheckoutAuthorizationException
     */
    public function shouldSendTheEncryptionKeyWithoutTheCvvFlag()
    {
        $headers = new CardUpdateHeaders();
        $headers->encryption_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A";

        $built = $this->buildRequestHeaders($headers);

        $this->assertArrayNotHasKey("return-encrypted-cvv", $built);
        $this->assertSame("MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A", $built["Encryption-Key"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldForwardTheHeadersToThePatchCall()
    {
        $request = new UpdateCardRequest();
        $request->reference = "X-123456-N11";

        $headers = new CardUpdateHeaders();
        $headers->return_encrypted_cvv = "true";
        $headers->encryption_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A";

        $this->apiClient
            ->expects($this->once())
            ->method("patch")
            ->with(
                $this->equalTo("issuing/cards/crd_12345"),
                $this->equalTo($request),
                $this->anything(),
                $this->equalTo($headers)
            )
            ->willReturn($this->buildExpectedUpdateCardResponse());

        $response = $this->client->updateCardDetails("crd_12345", $request, $headers);

        $this->assertNotNull($response);
        $this->assertSame("oJMoNMEEUiQKYOsQ4Zd", $response["encrypted_cvv"]);
        $this->assertArrayHasKey("last_modified_date", $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateCardDetailsWithoutHeaders()
    {
        $request = new UpdateCardRequest();

        $this->apiClient
            ->expects($this->once())
            ->method("patch")
            ->with(
                $this->equalTo("issuing/cards/crd_12345"),
                $this->equalTo($request),
                $this->anything(),
                $this->equalTo(null)
            )
            ->willReturn(["last_modified_date" => "2026-06-01T10:00:00Z"]);

        $response = $this->client->updateCardDetails("crd_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayNotHasKey("encrypted_cvv", $response);
    }

    /**
     * Exercises the private header builder on a real ApiClient, so the assertions pin the names
     * that actually reach the wire rather than the mapping declaration alone.
     *
     * @param CardUpdateHeaders $headers
     * @return array
     * @throws CheckoutAuthorizationException
     */
    private function buildRequestHeaders(CardUpdateHeaders $headers): array
    {
        $apiClient = new ApiClient($this->configuration);

        $method = new ReflectionMethod(ApiClient::class, "getHeaders");
        $method->setAccessible(true);

        return $method->invoke(
            $apiClient,
            new SdkAuthorization($this->platformType, "key"),
            "application/json",
            null,
            $headers
        );
    }

    private function buildExpectedUpdateCardResponse(): array
    {
        return [
            "last_modified_date" => "2026-06-01T10:00:00Z",
            "encrypted_cvv" => "oJMoNMEEUiQKYOsQ4Zd",
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/issuing/cards/crd_12345"]
            ]
        ];
    }
}
