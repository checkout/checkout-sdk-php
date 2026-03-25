<?php

namespace Checkout\Tests\Issuing\ControlProfiles;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Issuing\ControlProfiles\Requests\CreateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\UpdateControlProfileRequest;
use Checkout\Issuing\ControlProfiles\Requests\ControlProfileQuery;
use Checkout\Issuing\IssuingClient;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ControlProfilesClientTest extends UnitTestFixture
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
    public function shouldCreateControlProfile()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "cpr_12345"]);

        $request = $this->buildCreateControlProfileRequest();
        $response = $this->client->createControlProfile($request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("cpr_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAllControlProfiles()
    {
        $this->apiClient
            ->method("query")
            ->willReturn([
                "control_profiles" => [
                    ["id" => "cpr_12345", "name" => "Test Profile"]
                ]
            ]);

        $query = $this->buildControlProfileQuery();
        $response = $this->client->getControlProfiles($query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("control_profiles", $response);
        $this->assertCount(1, $response["control_profiles"]);
        $this->assertEquals("cpr_12345", $response["control_profiles"][0]["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetControlProfileDetails()
    {
        $this->apiClient
            ->method("get")
            ->willReturn([
                "id" => "cpr_12345",
                "name" => "Test Profile"
            ]);

        $response = $this->client->getControlProfileDetails("cpr_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("cpr_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateControlProfile()
    {
        $this->apiClient
            ->method("patch")
            ->willReturn(["id" => "cpr_12345"]);

        $request = $this->buildUpdateControlProfileRequest();
        $response = $this->client->updateControlProfile("cpr_12345", $request);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("cpr_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldDeleteControlProfile()
    {
        $this->apiClient
            ->method("delete")
            ->willReturn(["id" => "cpr_12345"]);

        $response = $this->client->removeControlProfile("cpr_12345");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("cpr_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAddTargetToControlProfile()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "cpr_12345"]);

        $response = $this->client->addTargetToControlProfile("cpr_12345", "crd_test");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("cpr_12345", $response["id"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRemoveTargetFromControlProfile()
    {
        $this->apiClient
            ->method("post")
            ->willReturn(["id" => "cpr_12345"]);

        $response = $this->client->removeTargetFromControlProfile("cpr_12345", "crd_test");

        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertEquals("cpr_12345", $response["id"]);
    }

    // Setup helpers
    private function buildCreateControlProfileRequest()
    {
        $request = new CreateControlProfileRequest();
        $request->name = "Test Control Profile";
        $request->description = "Test control profile for unit tests";
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
}
