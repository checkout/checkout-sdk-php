<?php

namespace Checkout\Tests\Disputes;

use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Disputes\DisputeEvidenceRequest;
use Checkout\Disputes\DisputesClient;
use Checkout\Disputes\DisputesQueryFilter;
use Checkout\Files\FileRequest;
use Checkout\PlatformType;
use Checkout\SdkCredentialsInterface;
use Checkout\Tests\UnitTestFixture;

class DisputesClientTest extends UnitTestFixture
{
    /**
     * @var DisputesClient
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
        $this->client = new DisputesClient($this->apiClient, $this->configuration, AuthorizationType::$secretKey);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldQueryDispute()
    {

        $this->apiClient
            ->method("query")
            ->willReturn(["foo"]);

        $response = $this->client->query(new DisputesQueryFilter());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeDetails()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getDisputeDetails("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAcceptDispute()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->accept("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPutEvidence()
    {

        $this->apiClient
            ->method("put")
            ->willReturn(["foo"]);

        $response = $this->client->putEvidence("dispute_id", new DisputeEvidenceRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEvidence()
    {

        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSubmitEvidence()
    {

        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);

        $response = $this->client->submitEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetCompiledSubmittedEvidence()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getCompiledSubmittedEvidence("dispute_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadFile()
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = self::getCheckoutFilePath();
        $fileRequest->purpose = 'dispute_evidence';

        $this->apiClient
            ->method("submitFile")
            ->willReturn(["foo"]);

        $response = $this->client->uploadFile($fileRequest);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * Ensures Disputes file upload sends Content-Length so the API accepts the request (INT-1597).
     */
    public function uploadFileSendsContentLengthHeader()
    {
        $capturedRequest = null;
        $stack = \GuzzleHttp\HandlerStack::create();
        $stack->after('prepare_body', function (callable $handler) use (&$capturedRequest) {
            return function ($request, array $options) use ($handler, &$capturedRequest) {
                $capturedRequest = $request;
                return $handler($request, $options);
            };
        }, 'capture_request');
        $stack->setHandler(function () {
            return \GuzzleHttp\Promise\Create::promiseFor(
                new \GuzzleHttp\Psr7\Response(200, ['Content-Type' => 'application/json'], '{"id":"file_123"}')
            );
        });
        $guzzleClient = new \GuzzleHttp\Client([
            'handler' => $stack,
            'base_uri' => 'https://api.sandbox.checkout.com/',
        ]);

        $sdkAuthorization = new \Checkout\SdkAuthorization(PlatformType::$default, 'sk_test_xxx');
        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials->method('getAuthorization')->willReturn($sdkAuthorization);
        $httpBuilder = $this->createMock(\Checkout\HttpClientBuilderInterface::class);
        $httpBuilder->method('getClient')->willReturn($guzzleClient);
        $logger = new \Monolog\Logger('checkout-sdk-test-php');
        $logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stderr'));
        $configuration = new \Checkout\CheckoutConfiguration(
            $sdkCredentials,
            \Checkout\Environment::sandbox(),
            $httpBuilder,
            $logger
        );
        $apiClient = new \Checkout\ApiClient($configuration);
        $disputesClient = new DisputesClient($apiClient, $configuration, AuthorizationType::$secretKey);

        $fileRequest = new FileRequest();
        $fileRequest->file = self::getCheckoutFilePath();
        $fileRequest->purpose = 'dispute_evidence';

        $disputesClient->uploadFile($fileRequest);

        $this->assertNotNull($capturedRequest, 'Expected the outgoing request to have been captured');
        $this->assertTrue(
            $capturedRequest->hasHeader('Content-Length'),
            'Disputes file upload must send Content-Length so the API accepts the request'
        );
        $this->assertGreaterThan(0, (int) $capturedRequest->getHeaderLine('Content-Length'));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFileDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getFileDetails("file_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetDisputeSchemeFiles()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);

        $response = $this->client->getDisputeSchemeFiles("dispute_id");
        $this->assertNotNull($response);
    }

}
