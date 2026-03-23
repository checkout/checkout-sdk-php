<?php

namespace Checkout\Tests\Issuing\ControlProfiles;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\ControlProfiles\Requests\CreateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\UpdateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\ControlProfileQuery;
use Checkout\Tests\Issuing\AbstractIssuingIntegrationTest;

class ControlProfilesIntegrationTest extends AbstractIssuingIntegrationTest
{
    private $controlProfile;

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function beforeAll()
    {
        $this->markTestSkipped("Avoid control profile tests, API returning 422");

        $this->before();
        $request = $this->buildCreateControlProfileRequest();
        $this->controlProfile = $this->issuingApi->getIssuingClient()->createControlProfile($request);
    }

    /**
     * @test
     */
    public function shouldCreateControlProfile()
    {
        $this->markTestSkipped("Prevent limit exceeded");
        
        $controlProfile = $this->controlProfile;

        $this->assertResponse($controlProfile, "id", "name");
        $this->assertNotNull($controlProfile["id"]);
        $this->assertEquals("Test Control Profile", $controlProfile["name"]);
        $this->assertTrue(substr($controlProfile["id"], 0, 4) === "cpr_");
        
        // Check timestamps exist
        if (array_key_exists("created_date", $controlProfile)) {
            $this->assertNotNull($controlProfile["created_date"]);
        }
        if (array_key_exists("last_modified_date", $controlProfile)) {
            $this->assertNotNull($controlProfile["last_modified_date"]);
        }
        if (array_key_exists("_links", $controlProfile)) {
            $this->assertNotNull($controlProfile["_links"]);
        }
    }

    /**
     * @test
     */
    public function shouldGetAllControlProfiles()
    {
        $this->markTestSkipped("Use on demand");

        $query = $this->buildControlProfileQuery();
        $response = $this->issuingApi->getIssuingClient()->getControlProfiles($query);

        $this->assertResponse($response, "control_profiles");
        $this->assertNotNull($response["control_profiles"]);

        // Find the specific control profile and compare with original
        $controlProfiles = $response["control_profiles"];
        $foundProfile = null;
        foreach ($controlProfiles as $profile) {
            if ($profile["id"] === $this->controlProfile["id"]) {
                $foundProfile = $profile;
                break;
            }
        }
        
        $this->assertNotNull($foundProfile, "Expected control profile {$this->controlProfile["id"]} not found in response");
        $this->assertEquals("Test Control Profile", $foundProfile["name"]);
        $this->assertDateFieldsMatch($this->controlProfile, $foundProfile);
    }

    /**
     * @test
     */
    public function shouldGetControlProfile()
    {
        $this->markTestSkipped("Use on demand");

        $response = $this->issuingApi->getIssuingClient()->getControlProfileDetails($this->controlProfile["id"]);

        $this->assertResponse($response, "id", "name");
        $this->assertEquals($this->controlProfile["id"], $response["id"]);
        $this->assertEquals("Test Control Profile", $response["name"]);
        $this->assertDateFieldsMatch($this->controlProfile, $response);
    }

    /**
     * @test
     */
    public function shouldUpdateControlProfile()
    {
        $this->markTestSkipped("Use on demand");

        $updateRequest = $this->buildUpdateControlProfileRequest();
        $response = $this->issuingApi->getIssuingClient()->updateControlProfile($this->controlProfile["id"], $updateRequest);

        $this->assertResponseWithId($response);
        $this->assertLastModifiedUpdated($this->controlProfile, $response);

        // Verify the update by fetching the profile
        $controlProfile = $this->issuingApi->getIssuingClient()->getControlProfileDetails($this->controlProfile["id"]);
        $this->assertResponse($controlProfile, "id", "name");
        $this->assertNotNull($controlProfile["id"]);
        $this->assertEquals("Updated Control Profile", $controlProfile["name"]);
        
        // Verify original created date is preserved, last modified matches response
        $this->assertDatesMatch($this->controlProfile, $controlProfile, "created_date");
        $this->assertDatesMatch($response, $controlProfile, "last_modified_date");
    }

    /**
     * @test
     */
    public function shouldAddTargetToControlProfile()
    {
        $this->markTestSkipped("Avoid creating cards all the time");

        $cardholder = $this->createCardholder();
        $abstractCardCreateRequest = $this->createCard($cardholder["id"]);
        $card = $this->issuingApi->getIssuingClient()->createCard($abstractCardCreateRequest);

        // Activate card before adding to control profile
        $this->issuingApi->getIssuingClient()->activateCard($card["id"]);
        
        $response = $this->issuingApi->getIssuingClient()->addTargetToControlProfile($this->controlProfile["id"], $card["id"]);

        $this->assertResponseWithId($response);
    }

    /**
     * @test
     */
    public function shouldRemoveTargetFromControlProfile()
    {
        $this->markTestSkipped("unavailable");

        $response = $this->issuingApi->getIssuingClient()->removeTargetFromControlProfile($this->controlProfile["id"], "card_1");

        $this->assertResponseWithId($response);
    }

    /**
     * @test
     */
    public function shouldRemoveControlProfile()
    {
        $this->markTestSkipped("Use on demand");

        $response = $this->issuingApi->getIssuingClient()->removeControlProfile($this->controlProfile["id"]);

        $this->assertResponseWithId($response);
    }

    // Setup helpers
    private function buildCreateControlProfileRequest()
    {
        $request = new CreateControlProfileRequest();
        $request->name = "Test Control Profile";
        $request->description = "Test control profile for integration tests";
        return $request;
    }

    private function buildUpdateControlProfileRequest()
    {
        $request = new UpdateControlProfileRequest();
        $request->name = "Updated Control Profile";
        $request->description = "Updated test control profile";
        return $request;
    }

    private function buildControlProfileQuery()
    {
        return new ControlProfileQuery();
    }

    // DRY helper methods for validation
    private function assertControlProfileStructure($controlProfile, $expectedName = null, $expectedDescription = null)
    {
        $this->assertNotNull($controlProfile["id"]);
        if ($expectedName !== null) {
            $this->assertEquals($expectedName, $controlProfile["name"]);
        }
        if ($expectedDescription !== null) {
            $this->assertEquals($expectedDescription, $controlProfile["description"]);
        }
        $this->assertTimestampFields($controlProfile);
    }

    private function assertTimestampFields($item)
    {
        $this->assertNotNull($item["created_on"]);
        $this->assertNotNull($item["last_modified"]);
    }

    private function assertResponseWithId($response, $additionalFields = [])
    {
        $this->assertResponse($response, array_merge(["id"], $additionalFields));
        $this->assertNotNull($response["id"]);
    }

    private function assertDatesMatch($originalItem, $comparisonItem, $dateField)
    {
        if (array_key_exists($dateField, $originalItem) && array_key_exists($dateField, $comparisonItem)) {
            $this->assertEquals(
                date('Y-m-d', strtotime($originalItem[$dateField])),
                date('Y-m-d', strtotime($comparisonItem[$dateField]))
            );
        }
    }

    private function assertDateFieldsMatch($originalItem, $comparisonItem)
    {
        $this->assertDatesMatch($originalItem, $comparisonItem, "created_date");
        $this->assertDatesMatch($originalItem, $comparisonItem, "last_modified_date");
    }

    private function assertLastModifiedUpdated($originalItem, $updatedItem)
    {
        if (array_key_exists("last_modified_date", $originalItem) && array_key_exists("last_modified_date", $updatedItem)) {
            $originalTime = strtotime($originalItem["last_modified_date"]);
            $updatedTime = strtotime($updatedItem["last_modified_date"]);
            $this->assertGreaterThan($originalTime, $updatedTime, "Last modified date should be greater than original");
        }
    }
}
