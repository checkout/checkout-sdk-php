<?php

namespace Checkout\Tests\Identities;

use Checkout\ApiClient;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
use Checkout\HttpClientBuilderInterface;
use Checkout\Identities\AddressDocumentVerification\AddressDocumentVerificationClient;
use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\Entities\AttemptsQueryFilter;
use Checkout\Identities\FaceAuthentication\FaceAuthenticationClient;
use Checkout\Identities\IdDocumentVerification\IdDocumentVerificationClient;
use Checkout\Identities\IdentityVerification\IdentityVerificationClient;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;

/**
 * Verifies that the pagination filters reach the outgoing request URI.
 *
 * The four client tests for the list-attempts endpoints mock ApiClient, and the filter encoding is
 * tested in isolation in IdentitiesSerializationTest. Neither covers the join between them, which
 * is ApiClient::query() appending the encoded parameters to the path. That join is the code this
 * change introduced, so it is asserted here against the real ApiClient with a stubbed Guzzle
 * handler, following the pattern of AccountsSchemaVersionHeaderTest.
 */
class AttemptsPaginationWireTest extends MockeryTestCase
{
    /**
     * @var RequestInterface|null
     */
    private $capturedRequest;

    /**
     * @var CheckoutConfiguration
     */
    private $configuration;

    /**
     * @var ApiClient
     */
    private $apiClient;

    /**
     * @before
     */
    public function init()
    {
        $this->capturedRequest = null;

        $stack = HandlerStack::create();
        $stack->after('prepare_body', function (callable $handler) {
            return function ($request, array $options) use ($handler) {
                $this->capturedRequest = $request;
                return $handler($request, $options);
            };
        }, 'capture_request');
        $stack->setHandler(function () {
            return \GuzzleHttp\Promise\Create::promiseFor(
                new Response(200, ['Content-Type' => 'application/json'], '{"total_count":0,"data":[]}')
            );
        });

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->method('getClient')->willReturn(new \GuzzleHttp\Client(['handler' => $stack]));

        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials
            ->method('getAuthorization')
            ->willReturn(new SdkAuthorization(PlatformType::$default, 'sk_sbox_key'));

        $logger = new Logger('checkout-sdk-test-php');
        $logger->pushHandler(new StreamHandler('php://stderr'));

        $this->configuration = new CheckoutConfiguration(
            $sdkCredentials,
            Environment::sandbox(),
            $httpBuilder,
            $logger
        );
        $this->apiClient = new ApiClient($this->configuration);
    }

    private function capturedUri(): string
    {
        $this->assertNotNull($this->capturedRequest, 'Expected the outgoing request to be captured');
        return (string)$this->capturedRequest->getUri();
    }

    private function paginationFilter(): AttemptsQueryFilter
    {
        $query = new AttemptsQueryFilter();
        $query->skip = 5;
        $query->limit = 25;

        return $query;
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendSkipAndLimitForAddressDocumentVerificationAttempts()
    {
        $client = new AddressDocumentVerificationClient($this->apiClient, $this->configuration);

        $client->getAddressDocumentVerificationAttempts("adv_123", $this->paginationFilter());

        $this->assertStringEndsWith(
            "address-document-verifications/adv_123/attempts?skip=5&limit=25",
            $this->capturedUri()
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendSkipAndLimitForIdDocumentVerificationAttempts()
    {
        $client = new IdDocumentVerificationClient($this->apiClient, $this->configuration);

        $client->getIdDocumentVerificationAttempts("iddv_123", $this->paginationFilter());

        $this->assertStringEndsWith(
            "id-document-verifications/iddv_123/attempts?skip=5&limit=25",
            $this->capturedUri()
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendSkipAndLimitForIdentityVerificationAttempts()
    {
        $client = new IdentityVerificationClient($this->apiClient, $this->configuration);

        $client->getIdentityVerificationAttempts("idv_123", $this->paginationFilter());

        $this->assertStringEndsWith(
            "identity-verifications/idv_123/attempts?skip=5&limit=25",
            $this->capturedUri()
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendSkipAndLimitForFaceAuthenticationAttempts()
    {
        $client = new FaceAuthenticationClient($this->apiClient, $this->configuration);

        $client->getFaceAuthenticationAttempts("fav_123", $this->paginationFilter());

        $this->assertStringEndsWith(
            "face-authentications/fav_123/attempts?skip=5&limit=25",
            $this->capturedUri()
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendSkipAndLimitForAttemptAssets()
    {
        $client = new AddressDocumentVerificationClient($this->apiClient, $this->configuration);

        $query = new AttemptAssetsQueryFilter();
        $query->skip = 2;
        $query->limit = 50;

        $client->getAddressDocumentVerificationAttemptAssets("adv_123", "adva_123", $query);

        $this->assertStringEndsWith(
            "address-document-verifications/adv_123/attempts/adva_123/assets?skip=2&limit=50",
            $this->capturedUri()
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendLimitOnlyWhenSkipIsNotSet()
    {
        $client = new IdentityVerificationClient($this->apiClient, $this->configuration);

        $query = new AttemptsQueryFilter();
        $query->limit = 25;

        $client->getIdentityVerificationAttempts("idv_123", $query);

        $this->assertStringEndsWith("identity-verifications/idv_123/attempts?limit=25", $this->capturedUri());
    }

    /**
     * A null filter must behave exactly like a plain get, with no trailing question mark. This is
     * the regression that ApiClient::query()'s null guard prevents.
     *
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendNoQueryStringWhenTheFilterIsOmitted()
    {
        $client = new IdentityVerificationClient($this->apiClient, $this->configuration);

        $client->getIdentityVerificationAttempts("idv_123");

        $uri = $this->capturedUri();
        $this->assertStringEndsWith("identity-verifications/idv_123/attempts", $uri);
        $this->assertStringNotContainsString("?", $uri);
    }

    /**
     * An empty filter encodes to an empty string, which must not produce a bare question mark.
     *
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendNoQueryStringWhenTheFilterIsEmpty()
    {
        $client = new IdentityVerificationClient($this->apiClient, $this->configuration);

        $client->getIdentityVerificationAttempts("idv_123", new AttemptsQueryFilter());

        $uri = $this->capturedUri();
        $this->assertStringEndsWith("identity-verifications/idv_123/attempts", $uri);
        $this->assertStringNotContainsString("?", $uri);
    }

    /**
     * The assets methods declared a nullable filter before this change but ApiClient::query() did
     * not accept one, so omitting it was a TypeError.
     *
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSendNoQueryStringWhenTheAssetsFilterIsOmitted()
    {
        $client = new IdDocumentVerificationClient($this->apiClient, $this->configuration);

        $client->getIdDocumentVerificationAttemptAssets("iddv_123", "datp_123");

        $uri = $this->capturedUri();
        $this->assertStringEndsWith("id-document-verifications/iddv_123/attempts/datp_123/assets", $uri);
        $this->assertStringNotContainsString("?", $uri);
    }
}
