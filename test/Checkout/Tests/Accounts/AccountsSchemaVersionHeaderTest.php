<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsClient;
use Checkout\Accounts\BankVerification;
use Checkout\Accounts\BankVerificationType;
use Checkout\Accounts\BusinessType;
use Checkout\Accounts\Company;
use Checkout\Accounts\Document;
use Checkout\Accounts\EntityRoles;
use Checkout\Accounts\OnboardEntityRequest;
use Checkout\Accounts\OnboardSubEntityDocuments;
use Checkout\Accounts\ProofOfRegistration;
use Checkout\Accounts\ProofOfRegistrationType;
use Checkout\Accounts\ProofOfResidentialAddress;
use Checkout\Accounts\ProofOfResidentialAddressType;
use Checkout\Accounts\Representative;
use Checkout\Accounts\RepresentativeDocuments;
use Checkout\ApiClient;
use Checkout\CheckoutConfiguration;
use Checkout\Common\DocumentType;
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

    /**
     * The EEA Sole Trader (3.0) representative documents cannot be exercised against the sandbox,
     * whose platform is not EEA-scoped, so this asserts the bytes that reach the wire instead: on
     * POST and PUT, the three documents sit under company.representatives[0].documents, on the v3.0
     * schema, and only bank_verification is sent at the top level.
     *
     * @test
     */
    public function eeaSoleTraderRepresentativeDocumentsReachTheWire()
    {
        $expectedRepresentativeDocuments = array(
            "identity_verification" => array("type" => "passport", "front" => "file_identityverificationaaaaaa"),
            "proof_of_residential_address" => array(
                "type" => "proof_of_address",
                "front" => "file_proofofresidentialaddressa",
            ),
            "proof_of_registration" => array(
                "type" => "extract_from_trade_register",
                "front" => "file_proofofregistrationaaaaaaa",
            ),
        );

        $this->buildAccountsClient()->createEntity($this->buildEeaSoleTraderRequest());
        $this->assertSame('POST', $this->capturedRequest->getMethod());
        $this->assertSame('/accounts/entities', $this->capturedRequest->getUri()->getPath());
        $this->assertAcceptHeader('application/json;schema_version=3.0');
        $body = json_decode((string) $this->capturedRequest->getBody(), true);
        $this->assertSame($expectedRepresentativeDocuments, $body['company']['representatives'][0]['documents']);
        $this->assertSame(array('bank_verification'), array_keys($body['documents']));

        $this->buildAccountsClient()->updateEntity('ent_123', $this->buildEeaSoleTraderRequest());
        $this->assertSame('PUT', $this->capturedRequest->getMethod());
        $this->assertSame('/accounts/entities/ent_123', $this->capturedRequest->getUri()->getPath());
        $body = json_decode((string) $this->capturedRequest->getBody(), true);
        $this->assertSame($expectedRepresentativeDocuments, $body['company']['representatives'][0]['documents']);
    }

    private function buildEeaSoleTraderRequest(): OnboardEntityRequest
    {
        $identity = new Document();
        $identity->type = DocumentType::$passport;
        $identity->front = "file_identityverificationaaaaaa";

        $proofOfResidentialAddress = new ProofOfResidentialAddress();
        $proofOfResidentialAddress->type = ProofOfResidentialAddressType::$proof_of_address;
        $proofOfResidentialAddress->front = "file_proofofresidentialaddressa";

        $proofOfRegistration = new ProofOfRegistration();
        $proofOfRegistration->type = ProofOfRegistrationType::$extract_from_trade_register;
        $proofOfRegistration->front = "file_proofofregistrationaaaaaaa";

        $representative = new Representative();
        $representative->roles = array(EntityRoles::$ubo);
        $representative->documents = new RepresentativeDocuments();
        $representative->documents->identity_verification = $identity;
        $representative->documents->proof_of_residential_address = $proofOfResidentialAddress;
        $representative->documents->proof_of_registration = $proofOfRegistration;

        $bankVerification = new BankVerification();
        $bankVerification->type = BankVerificationType::$bank_statement;
        $bankVerification->front = "file_bankverificationaaaaaaaaaa";

        $request = new OnboardEntityRequest();
        $request->reference = "ref_sole_trader";
        $request->company = new Company();
        $request->company->business_type = BusinessType::$individual_or_sole_proprietorship;
        $request->company->representatives = array($representative);
        $request->documents = new OnboardSubEntityDocuments();
        $request->documents->bank_verification = $bankVerification;
        return $request;
    }
}
