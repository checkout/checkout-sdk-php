<?php

namespace Checkout\Tests\Issuing\Cards;

use Checkout\ApiClient;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutConfiguration;
use Checkout\CheckoutException;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Checkout\Issuing\Cards\Update\CardUpdateHeaders;
use Checkout\Issuing\Cards\Update\UpdateCardRequest;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use Checkout\Tests\UnitTestFixture;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
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
     * The API answers 422 with error code encryption_key_required when return-encrypted-cvv is set
     * to true without an Encryption-Key header. Asserted through a stubbed Guzzle handler rather
     * than a mocked ApiClient, so the status code and the error body travel the real path into
     * CheckoutApiException.
     *
     * @test
     */
    public function shouldSurfaceThe422WhenTheEncryptionKeyIsMissing()
    {
        $client = $this->buildClientReturning(422, '{'
            . '"request_id":"0HLHPN8802NUF:00000003",'
            . '"error_type":"request_invalid",'
            . '"error_codes":["encryption_key_required"]'
            . '}');

        $headers = new CardUpdateHeaders();
        $headers->return_encrypted_cvv = "true";

        try {
            $client->updateCardDetails("crd_12345", new UpdateCardRequest(), $headers);
            $this->fail("Expected a CheckoutApiException for the 422 response");
        } catch (CheckoutApiException $e) {
            $this->assertSame(422, $e->http_metadata->getStatusCode());
            $this->assertSame("request_invalid", $e->error_details["error_type"]);
            $this->assertContains("encryption_key_required", $e->error_details["error_codes"]);
        }
    }

    /**
     * @test
     */
    public function shouldSucceedWhenBothHeadersAreSupplied()
    {
        $client = $this->buildClientReturning(200, '{'
            . '"last_modified_date":"2026-06-01T10:00:00Z",'
            . '"encrypted_cvv":"oJMoNMEEUiQKYOsQ4Zd"'
            . '}');

        $headers = new CardUpdateHeaders();
        $headers->return_encrypted_cvv = "true";
        $headers->encryption_key = "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A";

        $response = $client->updateCardDetails("crd_12345", new UpdateCardRequest(), $headers);

        $this->assertSame("oJMoNMEEUiQKYOsQ4Zd", $response["encrypted_cvv"]);
        $this->assertSame(200, $response["http_metadata"]->getStatusCode());
    }

    /**
     * Builds an IssuingClient whose HTTP layer always answers with the given status and body.
     *
     * @param int $status
     * @param string $body
     * @return IssuingClient
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    private function buildClientReturning(int $status, string $body): IssuingClient
    {
        $stack = HandlerStack::create();
        $stack->setHandler(function ($request, array $options) use ($status, $body) {
            $response = new Response($status, ['Content-Type' => 'application/json'], $body);
            if ($status >= 400) {
                return \GuzzleHttp\Promise\Create::rejectionFor(
                    new \GuzzleHttp\Exception\RequestException(
                        "error",
                        $request,
                        $response
                    )
                );
            }
            return \GuzzleHttp\Promise\Create::promiseFor($response);
        });

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->method("getClient")->willReturn(new \GuzzleHttp\Client(["handler" => $stack]));

        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials
            ->method("getAuthorization")
            ->willReturn(new SdkAuthorization(PlatformType::$default, "sk_sbox_key"));

        $logger = new Logger("checkout-sdk-test-php");
        $logger->pushHandler(new StreamHandler("php://stderr"));

        $configuration = new CheckoutConfiguration(
            $sdkCredentials,
            Environment::sandbox(),
            $httpBuilder,
            $logger
        );

        return new IssuingClient(new ApiClient($configuration), $configuration);
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
