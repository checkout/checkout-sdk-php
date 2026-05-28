<?php

namespace Checkout\Tests\Accounts;

use Checkout\Accounts\AccountsClient;
use Checkout\Accounts\EntityRequirementUpdateRequest;
use Checkout\ApiClient;
use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class AccountsClientEntityRequirementsTest extends UnitTestFixture
{
    /**
     * @var AccountsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $filesApiClient = $this->createMock(ApiClient::class);
        $this->client = new AccountsClient($this->apiClient, $filesApiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEntityRequirements()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "data" => [
                    [
                        "id" => "req_123",
                        "resource" => "ent_456",
                        "reason" => "periodic_review",
                        "priority" => "high"
                    ]
                ]
            ]);

        $response = $this->client->getEntityRequirements("entity_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEntityRequirementDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => "req_123",
                "resource" => "ent_456",
                "reason" => "periodic_review",
                "priority" => "high",
                "message" => "Please provide your ID",
                "_schema" => []
            ]);

        $response = $this->client->getEntityRequirementDetails("entity_id", "requirement_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldResolveEntityRequirement()
    {
        $this->apiClient
            ->method("put")
            ->willReturn([
                "id" => "req_123",
                "status" => "processing",
                "submitted_at" => "2026-05-05T10:15:30Z"
            ]);

        $request = new EntityRequirementUpdateRequest();
        $request->setValue(["file_id" => "file_test123"]);
        
        $response = $this->client->resolveEntityRequirement("entity_id", "requirement_id", $request);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldResolveEntityRequirementWithStringValue()
    {
        $this->apiClient
            ->method("put")
            ->willReturn([
                "id" => "req_123",
                "status" => "processing",
                "submitted_at" => "2026-05-05T10:15:30Z"
            ]);

        $request = new EntityRequirementUpdateRequest();
        $request->setValue("test_response_value");
        
        $response = $this->client->resolveEntityRequirement("entity_id", "requirement_id", $request);
        $this->assertNotNull($response);
    }
}
