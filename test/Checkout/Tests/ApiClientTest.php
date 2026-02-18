<?php

namespace Checkout\Tests;

use Checkout\ApiClient;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
use Checkout\Files\FileRequest;
use Checkout\HttpClientBuilderInterface;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\RequestInterface;

class ApiClientTest extends MockeryTestCase
{
    /**
     * @var RequestInterface|null
     */
    private $capturedRequest;

    /**
     * @return string
     */
    private function getCheckoutFilePath()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . "Resources" . DIRECTORY_SEPARATOR . "checkout.jpeg";
    }

    /**
     * Builds a Guzzle client that captures the outgoing request (after PrepareBody runs)
     * and returns a fake 200 JSON response.
     */
    private function createCapturingHttpClient(): \GuzzleHttp\Client
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
                new Response(200, ['Content-Type' => 'application/json'], '{"id":"file_123"}')
            );
        });

        return new \GuzzleHttp\Client([
            'handler' => $stack,
            'base_uri' => 'https://api.sandbox.checkout.com/',
        ]);
    }

    private function createConfiguration(): CheckoutConfiguration
    {
        $sdkAuthorization = new SdkAuthorization(PlatformType::$default, 'sk_test_xxx');
        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials->method('getAuthorization')->willReturn($sdkAuthorization);

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->method('getClient')->willReturn($this->createCapturingHttpClient());

        $logger = new Logger('checkout-sdk-test-php');
        $logger->pushHandler(new StreamHandler('php://stderr'));
        $logger->pushHandler(new StreamHandler('checkout-sdk-test-php.log'));

        return new CheckoutConfiguration(
            $sdkCredentials,
            Environment::sandbox(),
            $httpBuilder,
            $logger
        );
    }

    /**
     * @test
     */
    public function submitFileSendsRequestWithContentLengthHeader()
    {
        $configuration = $this->createConfiguration();
        $apiClient = new ApiClient($configuration);

        $fileRequest = new FileRequest();
        $fileRequest->file = $this->getCheckoutFilePath();
        $fileRequest->purpose = 'dispute_evidence';

        $authorization = new SdkAuthorization(PlatformType::$default, 'sk_test_xxx');

        $response = $apiClient->submitFile('files', $fileRequest, $authorization);

        // assertIsArray for PHPUnit 7.5+, assertThat+IsType for PHPUnit 5.7 (PHP 7.1) – avoids PHPStan/PHPUnit 9 removal of assertInternalType
        if (method_exists($this, 'assertIsArray')) {
            $this->assertIsArray($response);
        } else {
            $this->assertThat($response, new \PHPUnit\Framework\Constraint\IsType('array'));
        }
        $this->assertArrayHasKey('id', $response);
        $this->assertNotNull($this->capturedRequest, 'Expected the outgoing request to have been captured');

        $this->assertTrue(
            $this->capturedRequest->hasHeader('Content-Length'),
            'File upload request must include Content-Length header so the server accepts the upload'
        );

        $contentLength = (int) $this->capturedRequest->getHeaderLine('Content-Length');
        $this->assertGreaterThan(
            0,
            $contentLength,
            'Content-Length must be greater than zero for multipart file upload'
        );
    }

    /**
     * @test
     */
    public function submitFileContentLengthMatchesBodySize()
    {
        $configuration = $this->createConfiguration();
        $apiClient = new ApiClient($configuration);

        $fileRequest = new FileRequest();
        $fileRequest->file = $this->getCheckoutFilePath();
        $fileRequest->purpose = 'dispute_evidence';

        $authorization = new SdkAuthorization(PlatformType::$default, 'sk_test_xxx');

        $apiClient->submitFile('files', $fileRequest, $authorization);

        $this->assertNotNull($this->capturedRequest);
        $body = $this->capturedRequest->getBody();
        $bodySize = $body->getSize();
        $this->assertNotNull($bodySize, 'Body stream must report a size for correct Content-Length');
        $this->assertEquals(
            $bodySize,
            (int) $this->capturedRequest->getHeaderLine('Content-Length'),
            'Content-Length header must match the actual body size'
        );
    }
}
