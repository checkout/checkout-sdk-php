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
                        "id" => "req_5wmacwhrhbzhqkhx5hlqmzje44",
                        "resource" => "ent_azsiyswl7bwe2ynjzujy7lcjca",
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
                "id" => "req_5wmacwhrhbzhqkhx5hlqmzje44",
                "resource" => "ent_azsiyswl7bwe2ynjzujy7lcjca",
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
                "id" => "req_5wmacwhrhbzhqkhx5hlqmzje44",
                "status" => "processing",
                "submitted_at" => "2026-05-05T10:15:30Z"
            ]);

        $request = new EntityRequirementUpdateRequest();
        $request->value = ["file_id" => "file_awonj5x3g4oupitqp6dhsc4hyy"];
        
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
                "id" => "req_5wmacwhrhbzhqkhx5hlqmzje44",
                "status" => "processing",
                "submitted_at" => "2026-05-05T10:15:30Z"
            ]);

        $request = new EntityRequirementUpdateRequest();
        $request->value = "test_response_value";
        
        $response = $this->client->resolveEntityRequirement("entity_id", "requirement_id", $request);
        $this->assertNotNull($response);
    }
}
