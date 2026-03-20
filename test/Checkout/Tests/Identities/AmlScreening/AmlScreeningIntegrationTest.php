<?php

namespace Checkout\Tests\Identities\AmlScreening;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\AmlScreening\Requests\AmlScreeningRequest;
use Checkout\Identities\Entities\SearchParameters;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class AmlScreeningIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function before()
    {
        $this->init(PlatformType::$default);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAmlScreening()
    {
        $this->markTestSkipped("This test requires valid applicant ID and AML configuration");

        $request = $this->buildAmlScreeningRequest();

        $response = $this->checkoutApi->getAmlScreeningClient()
            ->createAmlScreening($request);

        $this->validateCreatedAmlScreening($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAmlScreening()
    {
        $this->markTestSkipped("This test requires valid applicant ID and AML configuration");

        // Create AML screening first
        $request = $this->buildAmlScreeningRequest();
        $createdAmlScreening = $this->checkoutApi->getAmlScreeningClient()
            ->createAmlScreening($request);

        // Get the created AML screening
        $response = $this->checkoutApi->getAmlScreeningClient()
            ->getAmlScreening($createdAmlScreening["id"]);

        $this->validateRetrievedAmlScreening($response, $createdAmlScreening);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAmlScreeningWithMonitoringDisabled()
    {
        $this->markTestSkipped("This test requires valid applicant ID and AML configuration");

        $request = $this->buildAmlScreeningRequest();
        $request->monitored = false;

        $response = $this->checkoutApi->getAmlScreeningClient()
            ->createAmlScreening($request);

        $this->validateCreatedAmlScreening($response, $request);
        $this->assertEquals(false, $response["monitored"]);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAndTrackAmlScreeningWorkflow()
    {
        $this->markTestSkipped("This test requires valid applicant ID and AML configuration");

        // Create AML screening
        $request = $this->buildAmlScreeningRequest();
        $createdAmlScreening = $this->checkoutApi->getAmlScreeningClient()
            ->createAmlScreening($request);

        // Wait for processing (simulating real-world usage)
        sleep(2);

        // Get updated status
        $updatedAmlScreening = $this->checkoutApi->getAmlScreeningClient()
            ->getAmlScreening($createdAmlScreening["id"]);

        $this->validateWorkflowProgression($createdAmlScreening, $updatedAmlScreening);
    }

    private function buildAmlScreeningRequest(): AmlScreeningRequest
    {
        $searchParameters = new SearchParameters();
        $searchParameters->configuration_identifier = $this->getEnvironmentVariable("CHECKOUT_TEST_AML_CONFIG_ID", "config_test_id");

        $request = new AmlScreeningRequest();
        $request->applicant_id = $this->getEnvironmentVariable("CHECKOUT_TEST_APPLICANT_ID", "aplt_test_applicant_id");
        $request->search_parameters = $searchParameters;
        $request->monitored = true;

        return $request;
    }

    private function getEnvironmentVariable(string $key, string $defaultValue): string
    {
        return getenv($key) ?: $defaultValue;
    }

    private function validateCreatedAmlScreening(array $response, AmlScreeningRequest $request): void
    {
        // Base validation
        $this->validateBaseAmlScreeningResponse($response);

        // Request-specific validation
        $this->assertEquals($request->applicant_id, $response["applicant_id"]);
        $this->assertEquals($request->search_parameters->configuration_identifier, $response["search_parameters"]["configuration_identifier"]);
        $this->assertEquals($request->monitored, $response["monitored"]);

        // Status should be initial state
        $this->assertContains($response["status"], ["created", "screening_in_progress"]);
    }

    private function validateRetrievedAmlScreening(array $retrieved, array $original): void
    {
        // Base validation
        $this->validateBaseAmlScreeningResponse($retrieved);

        // Identity validation
        $this->assertEquals($original["id"], $retrieved["id"]);
        $this->assertEquals($original["applicant_id"], $retrieved["applicant_id"]);
        $this->assertEquals($original["search_parameters"]["configuration_identifier"], $retrieved["search_parameters"]["configuration_identifier"]);
        $this->assertEquals($original["monitored"], $retrieved["monitored"]);

        // Timestamps should be consistent or updated
        $this->assertEquals($original["created_on"], $retrieved["created_on"]);
        if (isset($retrieved["modified_on"]) && isset($original["modified_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($original["modified_on"]),
                strtotime($retrieved["modified_on"])
            );
        }
    }

    private function validateWorkflowProgression(array $initial, array $updated): void
    {
        // Base validation for both responses
        $this->validateBaseAmlScreeningResponse($initial);
        $this->validateBaseAmlScreeningResponse($updated);

        // Identity should remain the same
        $this->assertEquals($initial["id"], $updated["id"]);
        $this->assertEquals($initial["applicant_id"], $updated["applicant_id"]);

        // Status may have progressed
        $this->assertContains($updated["status"], [
            "created",
            "screening_in_progress",
            "approved",
            "declined",
            "review_required"
        ]);

        // Timestamps should show progression
        $this->assertEquals($initial["created_on"], $updated["created_on"]);
        if (isset($updated["modified_on"]) && isset($initial["modified_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($initial["modified_on"]),
                strtotime($updated["modified_on"])
            );
        }
    }

    private function validateBaseAmlScreeningResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "applicant_id",
            "status",
            "search_parameters.configuration_identifier",
            "monitored",
            "created_on",
            "modified_on"
        );

        $this->assertStringStartsWith("scr_", $response["id"]);
        $this->assertStringStartsWith("aplt_", $response["applicant_id"]);

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
