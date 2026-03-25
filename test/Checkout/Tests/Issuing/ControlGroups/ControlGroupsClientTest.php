<?php

namespace Checkout\Tests\Issuing\ControlGroups;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\ControlGroups\Entities\Control;
use Checkout\Issuing\ControlGroups\Entities\FailIf;
use Checkout\Issuing\ControlGroups\Entities\MidLimit;
use Checkout\Issuing\ControlGroups\Entities\MidControlType;
use Checkout\Issuing\ControlGroups\Requests\ControlGroupQuery;
use Checkout\Issuing\ControlGroups\Requests\CreateControlGroupRequest;
use Checkout\Issuing\Controls\VelocityLimit;
use Checkout\Issuing\Controls\VelocityWindow;
use Checkout\Issuing\Controls\VelocityWindowType;
use Checkout\Issuing\Controls\MccLimit;
use Checkout\Issuing\Controls\MccControlType;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ControlGroupsClientTest extends UnitTestFixture
{
    /**
     * @var IssuingClient
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
        $this->client = new IssuingClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function createControlGroupWhenRequestIsValidShouldSucceed()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "cgr_test_12345"]);

        $request = $this->createValidControlGroupRequest();
        $response = $this->client->createControlGroup($request);

        $expectedResponse = ["id" => "cgr_test_12345"];
        $this->assertControlGroupResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function getTargetControlGroupsWhenQueryIsValidShouldSucceed()
    {
        $this->apiClient
            ->method("query")
            ->willReturn([
                "control_groups" => [
                    ["id" => "cgr_test_12345", "target_id" => "crd_test_12345"]
                ]
            ]);

        $query = $this->buildControlGroupQueryTarget();
        $response = $this->client->getControlGroups($query);

        $expectedResponse = [
            "control_groups" => [
                ["id" => "cgr_test_12345", "target_id" => "crd_test_12345"]
            ]
        ];
        $this->assertControlGroupsResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function getControlGroupDetailsWhenIdIsValidShouldSucceed()
    {
        $controlGroupId = "cgr_test_12345";
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => $controlGroupId,
                "target_id" => "crd_test_12345",
                "fail_if" => FailIf::$all_fail,
                "description" => "Test control group"
            ]);

        $response = $this->client->getControlGroupDetails($controlGroupId);

        $expectedResponse = [
            "id" => $controlGroupId,
            "target_id" => "crd_test_12345",
            "fail_if" => FailIf::$all_fail,
            "description" => "Test control group"
        ];
        $this->assertControlGroupResponse($response, $expectedResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function removeControlGroupWhenIdIsValidShouldSucceed()
    {
        $controlGroupId = "cgr_test_12345";
        $this->apiClient
            ->method("delete")
            ->willReturn(["id" => $controlGroupId]);

        $response = $this->client->removeControlGroup($controlGroupId);

        $expectedResponse = ["id" => $controlGroupId];
        $this->assertIdResponse($response, $expectedResponse);
    }

    // Setup helpers
    private function createValidControlGroupRequest()
    {
        // Create MCC control
        $mccLimit = new MccLimit();
        $mccLimit->type = MccControlType::$block;
        $mccLimit->mcc_list = ["5411", "5422"];

        $mccControl = new Control();
        $mccControl->control_type = "mcc_limit";
        $mccControl->description = "Test MCC control";
        $mccControl->mcc_limit = $mccLimit;

        // Create MID control
        $midLimit = new MidLimit();
        $midLimit->type = MidControlType::$allow;
        $midLimit->mid_list = ["1234567890"];

        $midControl = new Control();
        $midControl->control_type = "mid_limit";
        $midControl->description = "Allow specific merchant";
        $midControl->mid_limit = $midLimit;

        $request = new CreateControlGroupRequest();
        $request->target_id = "crd_test_12345";
        $request->fail_if = FailIf::$all_fail;
        $request->description = "Test control group";
        $request->controls = [$mccControl, $midControl];

        return $request;
    }

    private function buildControlGroupQueryTarget()
    {
        $query = new ControlGroupQuery();
        $query->target_id = "crd_test_12345";
        return $query;
    }

    // Validation helpers
    private function assertControlGroupResponse($actual, $expected)
    {
        $this->assertNotNull($actual);
        $this->assertArrayHasKey("id", $actual);
        $this->assertEquals($expected["id"], $actual["id"]);
    }

    private function assertControlGroupsResponse($actual, $expected)
    {
        $this->assertNotNull($actual);
        $this->assertArrayHasKey("control_groups", $actual);
        $this->assertNotNull($actual["control_groups"]);
        $this->assertCount(1, $actual["control_groups"]);
    }

    private function assertIdResponse($actual, $expected)
    {
        $this->assertNotNull($actual);
        $this->assertArrayHasKey("id", $actual);
        $this->assertEquals($expected["id"], $actual["id"]);
    }
}
