<?php

namespace Checkout\Tests\OnboardingSimulator;

use Checkout\CheckoutApiException;
use Checkout\OnboardingSimulator\OnboardingSimulatorClient;
use Checkout\OnboardingSimulator\Requests\SimulatorSetRequirementsDueRequest;
use Checkout\OnboardingSimulator\Requests\SimulatorSetStatusRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class OnboardingSimulatorClientTest extends UnitTestFixture
{
    /**
     * @var OnboardingSimulatorClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new OnboardingSimulatorClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSetRequirementsDue()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "entity_id" => "ent_123",
                "previous_status" => "Active",
                "current_status" => "requirements_due",
                "requirements_due" => ["individual.identification.document"]
            ]);

        $request = new SimulatorSetRequirementsDueRequest();
        $request->setFields(["individual.identification.document"]);
        
        $response = $this->client->setRequirementsDue("entity_id", $request);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRunScenario()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "entity_id" => "ent_123",
                "scenario_id" => "go_active",
                "scenario_name" => "Go Active",
                "previous_status" => "RequirementsDue",
                "current_status" => "Active",
                "requirements_due" => []
            ]);

        $response = $this->client->runScenario("entity_id", "scenario_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSetEntityStatus()
    {
        $this->apiClient
            ->method("put")
            ->willReturn([
                "entity_id" => "ent_123",
                "previous_status" => "Pending",
                "current_status" => "Active"
            ]);

        $request = new SimulatorSetStatusRequest();
        $request->setStatus("active");
        
        $response = $this->client->setEntityStatus("entity_id", $request);
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldListAvailableRequirements()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "data" => [
                    [
                        "field" => "individual.identification.document",
                        "type" => "string"
                    ]
                ]
            ]);

        $response = $this->client->listAvailableRequirements();
        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldListScenarios()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "data" => [
                    [
                        "id" => "go_active",
                        "name" => "Go Active",
                        "description" => "Transitions the entity to active status.",
                        "action" => "set_status",
                        "status" => "active"
                    ]
                ]
            ]);

        $response = $this->client->listScenarios();
        $this->assertNotNull($response);
        $this->assertTrue(is_array($response));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldSetRequirementsWithMultipleFields()
    {
        $this->apiClient
            ->method("post")
            ->willReturn([
                "entity_id" => "ent_123",
                "previous_status" => "Active",
                "current_status" => "requirements_due",
                "requirements_due" => [
                    "individual.identification.document",
                    "company.registration.document"
                ]
            ]);

        $request = new SimulatorSetRequirementsDueRequest();
        $request->setFields([
            "individual.identification.document",
            "company.registration.document"
        ]);
        
        $response = $this->client->setRequirementsDue("entity_id", $request);
        $this->assertNotNull($response);
    }
}
