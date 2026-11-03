<?php

namespace Checkout\Tests;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
use Checkout\Files\FileRequest;
use Checkout\HttpClientBuilderInterface;
use Checkout\OAuthSdkCredentials;
use Checkout\PlatformType;
use Checkout\SdkAuthorization;
use Checkout\SdkCredentialsInterface;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response;
use Mockery\Adapter\Phpunit\MockeryTestCase;
use Monolog\Handler\NullHandler;
use Monolog\Logger;

/**
 * The SDK must leave transport options to the HTTP client: requests use Guzzle's defaults
 * unless the caller configures the client through a custom HttpClientBuilderInterface.
 */
class HttpClientOptionsTest extends MockeryTestCase
{
    /**
     * @var array[] options of every request that reached the handler
     */
    private $capturedOptions = [];

    /**
     * @param array $clientConfig extra Guzzle client configuration
     * @param string $responseBody body of the fake 200 response
     * @return Client
     */
    private function createCapturingClient($clientConfig = [], $responseBody = '{"id":"res_123"}')
    {
        $this->capturedOptions = [];
        $test = $this;
        $stack = new HandlerStack(function ($request, array $options) use ($test, $responseBody) {
            $test->captureOptions($options);
            return new FulfilledPromise(
                new Response(200, ['Content-Type' => 'application/json'], $responseBody)
            );
        });
        return new Client(array_merge(['handler' => $stack], $clientConfig));
    }

    /**
     * @param array $options
     */
    public function captureOptions(array $options)
    {
        $this->capturedOptions[] = $options;
    }

    /**
     * @param Client $client
     * @return HttpClientBuilderInterface
     */
    private function createBuilder(Client $client)
    {
        $builder = $this->createMock(HttpClientBuilderInterface::class);
        $builder->method('getClient')->willReturn($client);
        return $builder;
    }

    /**
     * @param Client $client
     * @return ApiClient
     */
    private function createApiClient(Client $client)
    {
        $credentials = $this->createMock(SdkCredentialsInterface::class);
        $logger = new Logger('checkout-sdk-test-php');
        $logger->pushHandler(new NullHandler());
        return new ApiClient(new CheckoutConfiguration(
            $credentials,
            Environment::sandbox(),
            $this->createBuilder($client),
            $logger
        ));
    }

    /**
     * @return SdkAuthorization
     */
    private function authorization()
    {
        return new SdkAuthorization(PlatformType::$default, 'sk_test_xxx');
    }

    /**
     * @return FileRequest
     */
    private function fileRequest()
    {
        $fileRequest = new FileRequest();
        $fileRequest->file = __DIR__ . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . 'checkout.jpeg';
        $fileRequest->purpose = 'dispute_evidence';
        return $fileRequest;
    }

    /**
     * @param string $body
     * @param array $clientConfig
     * @return OAuthSdkCredentials
     */
    private function initOAuth($body, $clientConfig = [])
    {
        return OAuthSdkCredentials::init(
            $this->createBuilder($this->createCapturingClient($clientConfig, $body)),
            'client_id',
            'client_secret',
            'https://access.sandbox.checkout.com/connect/token',
            ['gateway']
        );
    }

    /**
     * @test
     */
    public function apiRequestsUseClientDefaultVerifyOption()
    {
        $apiClient = $this->createApiClient($this->createCapturingClient());

        $apiClient->get('payments/pay_123', $this->authorization());
        $apiClient->post('payments', ['amount' => 100], $this->authorization());
        $apiClient->submitFile('files', $this->fileRequest(), $this->authorization());

        $this->assertCount(3, $this->capturedOptions);
        foreach ($this->capturedOptions as $options) {
            $this->assertArrayHasKey('verify', $options);
            $this->assertTrue($options['verify']);
        }
    }

    /**
     * @test
     */
    public function apiRequestsHonourVerifyConfiguredOnCustomClient()
    {
        $caBundle = __DIR__ . DIRECTORY_SEPARATOR . 'ca-bundle.pem';
        $apiClient = $this->createApiClient($this->createCapturingClient(['verify' => $caBundle]));

        $apiClient->get('payments/pay_123', $this->authorization());
        $apiClient->submitFile('files', $this->fileRequest(), $this->authorization());

        $this->assertCount(2, $this->capturedOptions);
        foreach ($this->capturedOptions as $options) {
            $this->assertSame($caBundle, $options['verify']);
        }
    }

    /**
     * @test
     */
    public function tokenRequestUsesClientDefaultVerifyOption()
    {
        $this->initOAuth('{"access_token":"tok","token_type":"Bearer","expires_in":3600}');

        $this->assertCount(1, $this->capturedOptions);
        $this->assertTrue($this->capturedOptions[0]['verify']);
    }

    /**
     * @test
     */
    public function tokenRequestHonoursVerifyConfiguredOnCustomClient()
    {
        $caBundle = __DIR__ . DIRECTORY_SEPARATOR . 'ca-bundle.pem';
        $this->initOAuth(
            '{"access_token":"tok","token_type":"Bearer","expires_in":3600}',
            ['verify' => $caBundle]
        );

        $this->assertSame($caBundle, $this->capturedOptions[0]['verify']);
    }

    /**
     * @return array
     */
    public function validTokenResponses()
    {
        return [
            'numeric string expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":"3600"}'],
            'decimal expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":3599.5}'],
            'without token_type' => ['{"access_token":"tok","expires_in":3600}'],
            'expires_in of exactly one year' => ['{"access_token":"tok","token_type":"Bearer","expires_in":31536000}'],
        ];
    }

    /**
     * @test
     * @dataProvider validTokenResponses
     * @param string $body
     */
    public function validTokenResponseProducesAuthorization($body)
    {
        $credentials = $this->initOAuth($body);

        $authorization = $credentials->getAuthorization(AuthorizationType::$oAuth);

        $this->assertSame('Bearer tok', $authorization->getAuthorizationHeader());
    }

    /**
     * @return array
     */
    public function invalidTokenResponses()
    {
        return [
            'not json' => ['<html>error</html>'],
            'json scalar' => ['"tok"'],
            'empty object' => ['{}'],
            'missing access_token' => ['{"token_type":"Bearer","expires_in":3600}'],
            'empty access_token' => ['{"access_token":"","token_type":"Bearer","expires_in":3600}'],
            'non string access_token' => ['{"access_token":123,"token_type":"Bearer","expires_in":3600}'],
            'missing expires_in' => ['{"access_token":"tok","token_type":"Bearer"}'],
            'non numeric expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":"soon"}'],
            'negative expires_in' => ['{"access_token":"tok","token_type":"Bearer","expires_in":-1}'],
            'expires_in above one year' => ['{"access_token":"tok","token_type":"Bearer","expires_in":31536001}'],
            'expires_in beyond int range' => ['{"access_token":"tok","token_type":"Bearer","expires_in":9e18}'],
            'expires_in float overflow' => ['{"access_token":"tok","token_type":"Bearer","expires_in":1e30}'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidTokenResponses
     * @param string $body
     */
    public function invalidTokenResponseThrowsAuthorizationException($body)
    {
        $this->expectException(CheckoutAuthorizationException::class);

        $this->initOAuth($body);
    }
}
