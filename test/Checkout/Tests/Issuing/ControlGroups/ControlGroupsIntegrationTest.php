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
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class ControlGroupsIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $cardholder;
    private $card;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function beforeAll()
    {
        $this->before();
        $this->cardholder = $this->createCardholder();
        $this->card = $this->createCard($this->cardholder["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function createControlGroupShouldReturnValidResponse()
    {
        $request = $this->createValidControlGroupRequest($this->card["id"]);
        $response = $this->issuingApi->getIssuingClient()->createControlGroup($request);

        $this->assertControlGroupCreated($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function getTargetControlGroupsShouldReturnValidResponse()
    {
        // Arrange
        $query = $this->buildControlGroupQueryTarget($this->card["id"]);
        $controlGroupRequest = $this->createValidControlGroupRequest($this->card["id"]);
        $controlGroup = $this->issuingApi->getIssuingClient()->createControlGroup($controlGroupRequest);

        // Act
        $response = $this->issuingApi->getIssuingClient()->getControlGroups($query);

        // Assert
        $this->assertTargetControlGroupsRetrieved($response, $controlGroup["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function getControlGroupDetailsShouldReturnValidResponse()
    {
        // Arrange
        $controlGroupRequest = $this->createValidControlGroupRequest($this->card["id"]);
        $controlGroup = $this->issuingApi->getIssuingClient()->createControlGroup($controlGroupRequest);

        // Act
        $response = $this->issuingApi->getIssuingClient()->getControlGroupDetails($controlGroup["id"]);

        // Assert
        $this->assertControlGroupDetailsRetrieved($response, $controlGroup);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function removeControlGroupShouldReturnValidResponse()
    {
        // Arrange
        $request = $this->createValidControlGroupRequest($this->card["id"]);
        $createResponse = $this->issuingApi->getIssuingClient()->createControlGroup($request);

        // Act
        $response = $this->issuingApi->getIssuingClient()->removeControlGroup($createResponse["id"]);

        // Assert
        $this->assertControlGroupRemoved($response, $createResponse["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function controlGroupFlowShouldWorkEndToEnd()
    {
        // Arrange - Use the existing card
        $createRequest = $this->createValidControlGroupRequest($this->card["id"]);

        // Act 1: Create control group
        $createResponse = $this->issuingApi->getIssuingClient()->createControlGroup($createRequest);

        // Assert 1: Control group created successfully
        $this->assertControlGroupCreated($createResponse, $createRequest);

        // Act 2: Get target control groups
        $targetQuery = $this->buildControlGroupQueryTarget($this->card["id"]);
        $targetResponse = $this->issuingApi->getIssuingClient()->getControlGroups($targetQuery);

        // Assert 2: Target control groups retrieved
        $this->assertTargetControlGroupsRetrieved($targetResponse, $createResponse["id"]);

        // Act 3: Get control group details
        $detailsResponse = $this->issuingApi->getIssuingClient()->getControlGroupDetails($createResponse["id"]);

        // Assert 3: Details match created control group
        $this->assertControlGroupDetailsRetrieved($detailsResponse, $createResponse);

        // Act 4: Remove control group
        $removeResponse = $this->issuingApi->getIssuingClient()->removeControlGroup($createResponse["id"]);

        // Assert 4: Control group removed successfully
        $this->assertControlGroupRemoved($removeResponse, $createResponse["id"]);

        // Act 5: Verify control group no longer exists in target
        $finalTargetResponse = $this->issuingApi->getIssuingClient()->getControlGroups($targetQuery);

        // Assert 5: Control group no longer in target's control groups
        $this->assertControlGroupNotInTarget($finalTargetResponse, $createResponse["id"]);
    }

     /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPerformControlGroupFlow()
    {
        // Create
        $request = $this->createValidControlGroupRequest($this->card["id"]);
        $created = $this->issuingApi->getIssuingClient()->createControlGroup($request);
        $this->assertControlGroupCreated($created, $request);

        // Get details
        $details = $this->issuingApi->getIssuingClient()->getControlGroupDetails($created["id"]);
        $this->assertControlGroupDetailsRetrieved($details, $created);

        // Get all for target
        $query = $this->buildControlGroupQueryTarget($this->card["id"]);
        $groups = $this->issuingApi->getIssuingClient()->getControlGroups($query);
        $this->assertTargetControlGroupsRetrieved($groups, $created["id"]);

        // Remove
        $removed = $this->issuingApi->getIssuingClient()->removeControlGroup($created["id"]);
        $this->assertControlGroupRemoved($removed, $created["id"]);

        // Verify removal
        $afterRemoval = $this->issuingApi->getIssuingClient()->getControlGroups($query);
        $this->assertControlGroupNotInTarget($afterRemoval, $created["id"]);
    }
    
    // Setup helpers
    private function createValidControlGroupRequest($targetId)
    {
        // Create MCC control
        $mccLimit = new MccLimit();
        $mccLimit->type = MccControlType::$block;
        $mccLimit->mcc_list = ["5411", "5422"];

        $mccControl = new Control();
        $mccControl->control_type = "mcc_limit";
        $mccControl->description = "Block grocery stores";
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
        $request->target_id = $targetId;
        $request->fail_if = FailIf::$all_fail;
        $request->description = "Integration test control group";
        $request->controls = [$mccControl, $midControl];

        return $request;
    }

    private function buildControlGroupQueryTarget($targetId)
    {
        $query = new ControlGroupQuery();
        $query->target_id = $targetId;
        return $query;
    }

    // Validation helpers
    private function assertControlGroupCreated($response, $request)
    {
        $this->assertResponse($response, "id");
        $this->assertNotNull($response["id"]);
        $this->assertTrue(substr($response["id"], 0, 4) === "cgr_");
        $this->assertEquals($request->target_id, $response["target_id"]);
        $this->assertEquals($request->fail_if, $response["fail_if"]);
        $this->assertEquals($request->description, $response["description"]);
        $this->assertResponse($response, "controls");
        $this->assertCount(count($request->controls), $response["controls"]);
    }

    private function assertTargetControlGroupsRetrieved($response, $expectedControlGroupId)
    {
        $this->assertResponse($response, "control_groups");
        $this->assertTrue(is_array($response["control_groups"]));

        $found = false;
        foreach ($response["control_groups"] as $group) {
            if ($group["id"] === $expectedControlGroupId) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found, "Expected control group {$expectedControlGroupId} not found in response");
    }

    private function assertControlGroupDetailsRetrieved($response, $expected)
    {
        $this->assertResponse($response, "id", "target_id", "fail_if", "description");
        $this->assertEquals($expected["id"], $response["id"]);
        $this->assertEquals($expected["target_id"], $response["target_id"]);
        $this->assertEquals($expected["fail_if"], $response["fail_if"]);
        $this->assertEquals($expected["description"], $response["description"]);
        
        if (array_key_exists("controls", $response)) {
            $this->assertTrue(is_array($response["controls"]));
        }
    }

    private function assertControlGroupRemoved($response, $expectedId)
    {
        $this->assertResponse($response, "id");
        $this->assertEquals($expectedId, $response["id"]);
    }

    private function assertControlGroupNotInTarget($response, $controlGroupId)
    {
        // Only validate structure, not content - control_groups can be empty after removal
        $this->assertNotNull($response);
        $this->assertArrayHasKey("control_groups", $response);
        $this->assertTrue(is_array($response["control_groups"]));
        
        // If control_groups is empty, that's valid - the control group was successfully removed
        if (empty($response["control_groups"])) {
            return; // Test passes - no control groups means the removed one is definitely not there
        }
        
        // If there are control groups, make sure the removed one is not among them
        foreach ($response["control_groups"] as $group) {
            $this->assertNotEquals(
                $controlGroupId,
                $group["id"],
                "Control group {$controlGroupId} should not be present after removal"
            );
        }
    }
}
