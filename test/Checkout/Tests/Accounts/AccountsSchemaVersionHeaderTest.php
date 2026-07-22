<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsClient;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\ApiClient;
use Checkout\CheckoutConfiguration;
use Checkout\Environment;
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

class AccountsSchemaVersionHeaderTest extends MockeryTestCase
{
    /**
     * @var RequestInterface|null
     */
    private $capturedRequest;

    /**
     * Builds a Guzzle client that captures the outgoing request and returns a fake 200 JSON response.
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
                new Response(200, ['Content-Type' => 'application/json'], '{"id":"ent_123"}')
            );
        });

        return new \GuzzleHttp\Client([
            'handler' => $stack,
            'base_uri' => 'https://api.sandbox.checkout.com/',
        ]);
    }

    private function buildAccountsClient(): AccountsClient
    {
        $sdkAuthorization = new SdkAuthorization(PlatformType::$default, 'sk_test_xxx');
        $sdkCredentials = $this->createMock(SdkCredentialsInterface::class);
        $sdkCredentials->method('getAuthorization')->willReturn($sdkAuthorization);

        $httpBuilder = $this->createMock(HttpClientBuilderInterface::class);
        $httpBuilder->method('getClient')->willReturn($this->createCapturingHttpClient());

        $logger = new Logger('checkout-sdk-test-php');
        $logger->pushHandler(new StreamHandler('php://stderr'));
        $logger->pushHandler(new StreamHandler('checkout-sdk-test-php.log'));

        $configuration = new CheckoutConfiguration(
            $sdkCredentials,
            Environment::sandbox(),
            $httpBuilder,
            $logger
        );

        $apiClient = new ApiClient($configuration);
        return new AccountsClient($apiClient, $apiClient, $configuration);
    }

    private function assertAcceptHeader(string $expected)
    {
        $this->assertNotNull($this->capturedRequest, 'Expected the outgoing request to have been captured');
        $this->assertEquals(
            $expected,
            $this->capturedRequest->getHeaderLine('Accept'),
            'Accounts entity operations must negotiate the schema version through the Accept header'
        );
    }

    /**
     * @test
     */
    public function createEntitySendsSchemaVersion3ByDefault()
    {
        $this->buildAccountsClient()->createEntity(new OnboardEntityRequest());
        $this->assertAcceptHeader('application/json;schema_version=3.0');
    }

    /**
     * @test
     */
    public function getEntitySendsSchemaVersion3ByDefault()
    {
        $this->buildAccountsClient()->getEntity('ent_123');
        $this->assertAcceptHeader('application/json;schema_version=3.0');
    }

    /**
     * @test
     */
    public function updateEntitySendsSchemaVersion3ByDefault()
    {
        $this->buildAccountsClient()->updateEntity('ent_123', new OnboardEntityRequest());
        $this->assertAcceptHeader('application/json;schema_version=3.0');
    }

    /**
     * @test
     */
    public function getEntityRequirementsSendsSchemaVersion3ByDefault()
    {
        $this->buildAccountsClient()->getEntityRequirements('ent_123');
        $this->assertAcceptHeader('application/json;schema_version=3.0');
    }

    /**
     * @test
     */
    public function allowsOverridingTheSchemaVersion()
    {
        $this->buildAccountsClient()->getEntity('ent_123', '2.0');
        $this->assertAcceptHeader('application/json;schema_version=2.0');
    }
}
