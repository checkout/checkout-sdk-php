<?php

namespace Checkout\Tests\Forward;

use Checkout\CheckoutApiException;
use Checkout\Forward\ForwardClient;
use Checkout\Forward\Requests\ForwardRequest;
use Checkout\Forward\Requests\CreateSecretRequest;
use Checkout\Forward\Requests\UpdateSecretRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ForwardClientTest extends UnitTestFixture
{
    /**
     * @var ForwardClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new ForwardClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldForwardAnApiRequest()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["foo"]);
        $response = $this->client->forwardAnApiRequest(new ForwardRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetForwardRequest()
    {
        $this->apiClient
            ->method("get")
            ->willReturn(["foo"]);
        $response = $this->client->getForwardRequest("forward_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateSecret()
    {
        $expectedResponse = $this->buildExpectedSecretResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildCreateSecretRequest();
        $response = $this->client->createSecret($request);

        $this->assertNotNull($response);
        $this->validateSecretResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldListSecrets()
    {
        $expectedResponse = $this->buildExpectedSecretsListResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->listSecrets();

        $this->assertNotNull($response);
        $this->validateSecretsListResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateSecret()
    {
        $expectedResponse = $this->buildExpectedSecretResponse();
        
        $this->apiClient
            ->method("patch")
            ->willReturn($expectedResponse);

        $request = $this->buildUpdateSecretRequest();
        $response = $this->client->updateSecret("test_secret", $request);

        $this->assertNotNull($response);
        $this->validateSecretResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteSecret()
    {
        $expectedResponse = [];
        
        $this->apiClient
            ->method("delete")
            ->willReturn($expectedResponse);

        $response = $this->client->deleteSecret("test_secret");

        $this->assertNotNull($response);
        $this->assertEquals([], $response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateSecret()
    {
        $expectedResponse = $this->buildExpectedSecretResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("forward/secrets")
            ->willReturn($expectedResponse);

        $request = $this->buildCreateSecretRequest();
        $response = $this->client->createSecret($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForListSecrets()
    {
        $expectedResponse = $this->buildExpectedSecretsListResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("forward/secrets")
            ->willReturn($expectedResponse);

        $response = $this->client->listSecrets();

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForUpdateSecret()
    {
        $secretName = "test_secret";
        $expectedResponse = $this->buildExpectedSecretResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("patch")
            ->with("forward/secrets/" . $secretName)
            ->willReturn($expectedResponse);

        $request = $this->buildUpdateSecretRequest();
        $response = $this->client->updateSecret($secretName, $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForDeleteSecret()
    {
        $secretName = "test_secret";
        $expectedResponse = [];
        
        $this->apiClient
            ->expects($this->once())
            ->method("delete")
            ->with("forward/secrets/" . $secretName)
            ->willReturn($expectedResponse);

        $response = $this->client->deleteSecret($secretName);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleCreateSecretWithAllParameters()
    {
        $expectedResponse = $this->buildExpectedSecretResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildCreateSecretRequest();
        $request->entity_id = "ent_w4jelhppmfiufdnatam37wrfc4";
        
        $response = $this->client->createSecret($request);

        $this->assertNotNull($response);
        $this->validateSecretResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleUpdateSecretWithAllParameters()
    {
        $expectedResponse = $this->buildExpectedSecretResponse();
        
        $this->apiClient
            ->method("patch")
            ->willReturn($expectedResponse);

        $request = $this->buildUpdateSecretRequest();
        $request->entity_id = "ent_w4jelhppmfiufdnatam37wrfc4";
        
        $response = $this->client->updateSecret("test_secret", $request);

        $this->assertNotNull($response);
        $this->validateSecretResponse($response);
    }

    private function buildCreateSecretRequest(): CreateSecretRequest
    {
        $request = new CreateSecretRequest();
        $request->name = "test_secret";
        $request->value = "test_plaintext_value";

        return $request;
    }

    private function buildUpdateSecretRequest(): UpdateSecretRequest
    {
        $request = new UpdateSecretRequest();
        $request->value = "updated_plaintext_value";

        return $request;
    }

    private function buildExpectedSecretResponse(): array
    {
        return [
            "name" => "test_secret",
            "entity_id" => "ent_w4jelhppmfiufdnatam37wrfc4",
            "version" => 1,
            "created_on" => "2024-03-20T10:30:00Z",
            "modified_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedSecretsListResponse(): array
    {
        return [
            "data" => [
                [
                    "name" => "test_secret_1",
                    "entity_id" => "ent_w4jelhppmfiufdnatam37wrfc4",
                    "version" => 1,
                    "created_on" => "2024-03-20T10:30:00Z",
                    "modified_on" => "2024-03-20T10:30:00Z"
                ],
                [
                    "name" => "test_secret_2",
                    "entity_id" => "ent_w4jelhppmfiufdnatam37wrfc4",
                    "version" => 2,
                    "created_on" => "2024-03-20T09:15:00Z",
                    "modified_on" => "2024-03-20T11:45:00Z"
                ]
            ]
        ];
    }

    private function validateSecretResponse(array $response): void
    {
        $this->assertArrayHasKey("name", $response);
        $this->assertArrayHasKey("version", $response);
        $this->assertArrayHasKey("created_on", $response);
        $this->assertArrayHasKey("modified_on", $response);
        
        $this->assertNotNull($response["name"]);
        $this->assertNotNull($response["version"]);
        $this->assertNotNull($response["created_on"]);
        $this->assertNotNull($response["modified_on"]);
        $this->assertTrue(is_int($response["version"]));
        $this->assertGreaterThan(0, $response["version"]);
    }

    private function validateSecretsListResponse(array $response): void
    {
        $this->assertArrayHasKey("data", $response);
        $this->assertTrue(is_array($response["data"]));
        
        foreach ($response["data"] as $secret) {
            $this->validateSecretResponse($secret);
        }
    }
}
