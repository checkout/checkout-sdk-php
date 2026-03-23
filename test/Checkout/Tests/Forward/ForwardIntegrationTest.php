<?php

namespace Checkout\Tests\Forward;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Forward\Entities\Headers;
use Checkout\Forward\Entities\MethodType;
use Checkout\Forward\Entities\NetworkToken;
use Checkout\Forward\Entities\Signatures\DlocalParameters;
use Checkout\Forward\Entities\Signatures\DlocalSignature;
use Checkout\Forward\Entities\Sources\IdSource;
use Checkout\Forward\Requests\DestinationRequest;
use Checkout\Forward\Requests\ForwardRequest;
use Checkout\Forward\Requests\CreateSecretRequest;
use Checkout\Forward\Requests\UpdateSecretRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ForwardIntegrationTest extends SandboxTestFixture
{

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldForwardAnApiRequest()
    {
        $this->markTestSkipped("This test requires a valid id or token source");

        $forwardRequest = $this->createForwardRequest();

        $forwardResponse = $this->checkoutApi->getForwardClient()->forwardAnApiRequest($forwardRequest);

        $this->assertResponse(
            $forwardResponse,
            "request_id",
            "destination_response",
            "created_on",
            "reference",
            "processing_channel_id",
            "source",
            "destination_request"
        );

        $this->assertEquals($forwardRequest->reference, $forwardResponse["reference"]);
        $this->assertEquals($forwardRequest->processing_channel_id, $forwardResponse["processing_channel_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetForwardRequest()
    {
        $this->markTestSkipped("This test requires a valid id or token source");

        $forwardRequest = $this->createForwardRequest();

        $forwardResponse = $this->checkoutApi->getForwardClient()->forwardAnApiRequest($forwardRequest);

        $getForwardResponse = $this->checkoutApi->getForwardClient()->getForwardRequest($forwardResponse["request_id"]);

        $this->assertResponse(
            $getForwardResponse,
            "request_id",
            "entity_id",
            "created_on",
            "reference",
            "destination_request",
            "destination_response"
        );

        $this->assertEquals($forwardResponse["request_id"], $getForwardResponse["request_id"]);
        $this->assertEquals($forwardRequest->reference, $getForwardResponse["reference"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateSecret()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        $request = $this->buildCreateSecretRequest();

        $response = $this->checkoutApi->getForwardClient()
            ->createSecret($request);

        $this->validateCreatedSecret($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldListSecrets()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        $response = $this->checkoutApi->getForwardClient()
            ->listSecrets();

        $this->validateSecretsListResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateSecret()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        // Create secret first
        $createRequest = $this->buildCreateSecretRequest();
        $createdSecret = $this->checkoutApi->getForwardClient()
            ->createSecret($createRequest);

        // Update the secret
        $updateRequest = $this->buildUpdateSecretRequest();
        $response = $this->checkoutApi->getForwardClient()
            ->updateSecret($createdSecret["name"], $updateRequest);

        $this->validateUpdatedSecret($response, $createdSecret, $updateRequest);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteSecret()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        // Create secret first
        $createRequest = $this->buildCreateSecretRequest();
        $createdSecret = $this->checkoutApi->getForwardClient()
            ->createSecret($createRequest);

        // Delete the secret
        $response = $this->checkoutApi->getForwardClient()
            ->deleteSecret($createdSecret["name"]);

        $this->validateDeletedSecret($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateListUpdateDeleteSecretWorkflow()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        // Create secret
        $createRequest = $this->buildCreateSecretRequest();
        $createdSecret = $this->checkoutApi->getForwardClient()
            ->createSecret($createRequest);

        // List secrets to verify creation
        $listResponse = $this->checkoutApi->getForwardClient()
            ->listSecrets();

        // Update secret
        $updateRequest = $this->buildUpdateSecretRequest();
        $updatedSecret = $this->checkoutApi->getForwardClient()
            ->updateSecret($createdSecret["name"], $updateRequest);

        // List again to verify update
        $listResponseAfterUpdate = $this->checkoutApi->getForwardClient()
            ->listSecrets();

        // Delete secret
        $deleteResponse = $this->checkoutApi->getForwardClient()
            ->deleteSecret($createdSecret["name"]);

        $this->validateSecretWorkflowProgression(
            $createdSecret,
            $listResponse,
            $updatedSecret,
            $listResponseAfterUpdate,
            $deleteResponse,
            $createRequest,
            $updateRequest
        );
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateSecretWithOptionalFields()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        $request = $this->buildCreateSecretRequest();
        $request->entity_id = getenv("CHECKOUT_DEFAULT_ENTITY_ID");

        $response = $this->checkoutApi->getForwardClient()
            ->createSecret($request);

        $this->validateCreatedSecret($response, $request);
        $this->assertEquals($request->entity_id, $response["entity_id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateSecretWithEntityId()
    {
        $this->markTestSkipped("This test requires forward secrets scopes and valid credentials");

        // Create secret first
        $createRequest = $this->buildCreateSecretRequest();
        $createdSecret = $this->checkoutApi->getForwardClient()
            ->createSecret($createRequest);

        // Update with entity_id
        $updateRequest = $this->buildUpdateSecretRequest();
        $updateRequest->entity_id = getenv("CHECKOUT_DEFAULT_ENTITY_ID");

        $response = $this->checkoutApi->getForwardClient()
            ->updateSecret($createdSecret["name"], $updateRequest);

        $this->validateUpdatedSecret($response, $createdSecret, $updateRequest);
        $this->assertEquals($updateRequest->entity_id, $response["entity_id"]);
    }

    private function createForwardRequest()
    {
        $source = new IdSource();
        $source->id = "src_v5rgkf3gdtpuzjqesyxmyodnya";

        $headers = new Headers();
        $headers->raw = array(
            "Idempotency-Key" => "xe4fad12367dfgrds",
            "Content-Type" => "application/json"
        );
        $headers->encrypted = "<JWE encrypted JSON object with string values>";

        $dlocalParameters = new DlocalParameters();
        $dlocalParameters->secret_key = "9f439fe1a9f96e67b047d3c1a28c33a2e";

        $signature = new DlocalSignature();
        $signature->dlocal_parameters = $dlocalParameters;


        $destinationRequest = new DestinationRequest();
        $destinationRequest->url = "https://example.com/payments";
        $destinationRequest->method = MethodType::$post;
        $destinationRequest->headers = $headers;
        $destinationRequest->body = json_encode(array(
            "amount" => 1000,
            "currency" => "USD",
            "reference" => "some_reference",
            "source" => array(
                "type" => "card",
                "number" => "{{card_number}}",
                "expiry_month" => "{{card_expiry_month}}",
                "expiry_year" => "{{card_expiry_year_yyyy}}",
                "name" => "Ali Farid"
            ),
            "payment_type" => "Regular",
            "authorization_type" => "Final",
            "capture" => true,
            "processing_channel_id" => "pc_xxxxxxxxxxx",
            "risk" => array("enabled" => false),
            "merchant_initiated" => true
        ));
        $destinationRequest->signature = $signature;

        $networkToken = new NetworkToken();
        $networkToken->enabled = true;
        $networkToken->request_cryptogram = false;

        $forwardRequest = new ForwardRequest();
        $forwardRequest->source = $source;
        $forwardRequest->reference = "ORD-5023-4E89";
        $forwardRequest->processing_channel_id = "pc_azsiyswl7bwe2ynjzujy7lcjca";
        $forwardRequest->network_token = $networkToken;
        $forwardRequest->destination_request = $destinationRequest;

        return $forwardRequest;
    }

    private function buildCreateSecretRequest(): CreateSecretRequest
    {
        $request = new CreateSecretRequest();
        $request->name = "test_secret_" . uniqid();
        $request->value = "test_plaintext_value_" . uniqid();

        return $request;
    }

    private function buildUpdateSecretRequest(): UpdateSecretRequest
    {
        $request = new UpdateSecretRequest();
        $request->value = "updated_plaintext_value_" . uniqid();

        return $request;
    }

    private function validateCreatedSecret(array $response, CreateSecretRequest $request): void
    {
        // Base validation
        $this->validateBaseSecretResponse($response);

        // Request-specific validation
        $this->assertEquals($request->name, $response["name"]);
        
        if (isset($request->entity_id)) {
            $this->assertEquals($request->entity_id, $response["entity_id"]);
        }

        // Initial version should be 1
        $this->assertEquals(1, $response["version"]);
    }

    private function validateSecretsListResponse(array $response): void
    {
        $this->assertArrayHasKey("data", $response);
        $this->assertTrue(is_array($response["data"]));
        
        foreach ($response["data"] as $secret) {
            $this->validateBaseSecretResponse($secret);
        }
    }

    private function validateUpdatedSecret(array $updated, array $original, UpdateSecretRequest $updateRequest): void
    {
        // Base validation
        $this->validateBaseSecretResponse($updated);

        // Identity should remain the same
        $this->assertEquals($original["name"], $updated["name"]);

        // Version should increment
        $this->assertGreaterThan($original["version"], $updated["version"]);

        // Updated fields should reflect changes
        if (isset($updateRequest->entity_id)) {
            $this->assertEquals($updateRequest->entity_id, $updated["entity_id"]);
        }

        // Timestamps should show progression
        $this->assertEquals($original["created_on"], $updated["created_on"]);
        if (isset($updated["modified_on"]) && isset($original["modified_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($original["modified_on"]),
                strtotime($updated["modified_on"])
            );
        }
    }

    private function validateDeletedSecret(array $response): void
    {
        // Delete response should be empty array
        $this->assertEquals([], $response);
    }

    private function validateSecretWorkflowProgression(
        array $created,
        array $listBefore,
        array $updated,
        array $listAfter,
        array $deleted,
        CreateSecretRequest $createRequest,
        UpdateSecretRequest $updateRequest
    ): void {
        // Created secret should match request
        $this->assertEquals($createRequest->name, $created["name"]);
        $this->assertEquals(1, $created["version"]);

        // List should contain the created secret
        $this->assertArrayHasKey("data", $listBefore);
        $secretFound = false;
        foreach ($listBefore["data"] as $secret) {
            if ($secret["name"] === $created["name"]) {
                $secretFound = true;
                break;
            }
        }
        $this->assertTrue($secretFound, "Created secret should appear in list");

        // Updated secret should have incremented version
        $this->assertEquals($created["name"], $updated["name"]);
        $this->assertGreaterThan($created["version"], $updated["version"]);
        $this->assertEquals($created["created_on"], $updated["created_on"]);

        // List after update should reflect changes
        $this->assertArrayHasKey("data", $listAfter);
        $updatedSecretFound = false;
        foreach ($listAfter["data"] as $secret) {
            if ($secret["name"] === $updated["name"]) {
                $updatedSecretFound = true;
                $this->assertEquals($updated["version"], $secret["version"]);
                break;
            }
        }
        $this->assertTrue($updatedSecretFound, "Updated secret should appear in list with new version");

        // Delete response should be empty
        $this->assertEquals([], $deleted);
    }

    private function validateBaseSecretResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "name",
            "version",
            "created_on",
            "modified_on"
        );

        // Validate name format (1-64 alphanumeric and underscore)
        $this->assertMatchesRegularExpression('/^[a-zA-Z0-9_]{1,64}$/', $response["name"]);

        // Version should be positive integer
        $this->assertTrue(is_int($response["version"]));
        $this->assertGreaterThan(0, $response["version"]);

        // Validate timestamps
        if (isset($response["created_on"])) {
            $this->assertLessThanOrEqual(time(), strtotime($response["created_on"]));
        }
        if (isset($response["modified_on"])) {
            $this->assertLessThanOrEqual(time(), strtotime($response["modified_on"]));
        }
        if (isset($response["modified_on"]) && isset($response["created_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($response["created_on"]),
                strtotime($response["modified_on"])
            );
        }
    }
}
