<?php

namespace Checkout\Tests\Identities\Applicants;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Applicants\Requests\ApplicantRequest;
use Checkout\Identities\Applicants\Requests\ApplicantUpdateRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ApplicantsIntegrationTest extends SandboxTestFixture
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
    public function shouldCreateApplicant()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildApplicantRequest();

        $response = $this->checkoutApi->getApplicantsClient()
            ->createApplicant($request);

        $this->validateCreatedApplicant($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetApplicant()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create applicant first
        $request = $this->buildApplicantRequest();
        $createdApplicant = $this->checkoutApi->getApplicantsClient()
            ->createApplicant($request);

        // Get the created applicant
        $response = $this->checkoutApi->getApplicantsClient()
            ->getApplicant($createdApplicant["id"]);

        $this->validateRetrievedApplicant($response, $createdApplicant);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateApplicant()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create applicant first
        $request = $this->buildApplicantRequest();
        $createdApplicant = $this->checkoutApi->getApplicantsClient()
            ->createApplicant($request);
        
        // Update the applicant
        $updateRequest = $this->buildApplicantUpdateRequest();
        $response = $this->checkoutApi->getApplicantsClient()
            ->updateApplicant($createdApplicant["id"], $updateRequest);

        $this->validateUpdatedApplicant($response, $createdApplicant, $updateRequest);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeApplicant()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create applicant first
        $request = $this->buildApplicantRequest();
        $createdApplicant = $this->checkoutApi->getApplicantsClient()
            ->createApplicant($request);

        // Anonymize the applicant
        $response = $this->checkoutApi->getApplicantsClient()
            ->anonymizeApplicant($createdApplicant["id"]);

        $this->validateAnonymizedApplicant($response, $createdApplicant);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateUpdateAndRetrieveApplicantWorkflow()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create applicant
        $request = $this->buildApplicantRequest();
        $createdApplicant = $this->checkoutApi->getApplicantsClient()
            ->createApplicant($request);

        // Update applicant
        $updateRequest = $this->buildApplicantUpdateRequest();
        $updatedApplicant = $this->checkoutApi->getApplicantsClient()
            ->updateApplicant($createdApplicant["id"], $updateRequest);

        // Retrieve updated applicant
        $retrievedApplicant = $this->checkoutApi->getApplicantsClient()
            ->getApplicant($createdApplicant["id"]);

        $this->validateWorkflowProgression($createdApplicant, $updatedApplicant, $retrievedApplicant, $updateRequest);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldValidateOptionalFields()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create minimal request with only required fields
        $request = new ApplicantRequest();
        $request->email = $this->generateRandomEmail();
        // Leave optional fields null to test minimal requirements

        $response = $this->checkoutApi->getApplicantsClient()
            ->createApplicant($request);

        $this->validateBaseApplicantResponse($response);
        $this->assertEquals($request->email, $response["email"]);
    }

    private function buildApplicantRequest(): ApplicantRequest
    {
        $request = new ApplicantRequest();
        $request->external_applicant_id = "ext_test_" . uniqid();
        $request->email = $this->generateRandomEmail();
        $request->external_applicant_name = "Test Applicant Name";

        return $request;
    }

    private function buildApplicantUpdateRequest(): ApplicantUpdateRequest
    {
        $request = new ApplicantUpdateRequest();
        $request->email = "test.applicant.updated." . uniqid() . "@checkout.com";
        $request->external_applicant_name = "Updated Test Applicant Name";

        return $request;
    }

    private function generateRandomEmail(): string
    {
        return "test.applicant." . uniqid() . "@checkout.com";
    }

    private function validateCreatedApplicant(array $response, ApplicantRequest $request): void
    {
        // Base validation
        $this->validateBaseApplicantResponse($response);

        // Request-specific validation
        $this->assertEquals($request->email, $response["email"]);
        $this->assertEquals($request->external_applicant_name, $response["external_applicant_name"]);
        
        if (isset($request->external_applicant_id)) {
            $this->assertEquals($request->external_applicant_id, $response["external_applicant_id"]);
        }
    }

    private function validateRetrievedApplicant(array $retrieved, array $original): void
    {
        // Base validation
        $this->validateBaseApplicantResponse($retrieved);

        // Identity validation
        $this->assertEquals($original["id"], $retrieved["id"]);
        $this->assertEquals($original["email"], $retrieved["email"]);
        $this->assertEquals($original["external_applicant_name"], $retrieved["external_applicant_name"]);
        
        if (isset($original["external_applicant_id"])) {
            $this->assertEquals($original["external_applicant_id"], $retrieved["external_applicant_id"]);
        }

        // Timestamps should be consistent or updated
        $this->assertEquals($original["created_on"], $retrieved["created_on"]);
        if (isset($retrieved["modified_on"]) && isset($original["modified_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($original["modified_on"]),
                strtotime($retrieved["modified_on"])
            );
        }
    }

    private function validateUpdatedApplicant(array $updated, array $original, ApplicantUpdateRequest $updateRequest): void
    {
        // Base validation
        $this->validateBaseApplicantResponse($updated);

        // Identity should remain the same
        $this->assertEquals($original["id"], $updated["id"]);
        if (isset($original["external_applicant_id"])) {
            $this->assertEquals($original["external_applicant_id"], $updated["external_applicant_id"]);
        }

        // Updated fields should reflect changes
        $this->assertEquals($updateRequest->email, $updated["email"]);
        $this->assertEquals($updateRequest->external_applicant_name, $updated["external_applicant_name"]);

        // Timestamps should show progression
        $this->assertEquals($original["created_on"], $updated["created_on"]);
        if (isset($updated["modified_on"]) && isset($original["modified_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($original["modified_on"]),
                strtotime($updated["modified_on"])
            );
        }
    }

    private function validateAnonymizedApplicant(array $anonymized, array $original): void
    {
        // Base validation
        $this->validateBaseApplicantResponse($anonymized);

        // Identity should remain the same
        $this->assertEquals($original["id"], $anonymized["id"]);
    }

    private function validateWorkflowProgression(array $created, array $updated, array $retrieved, ApplicantUpdateRequest $updateRequest): void
    {
        // All responses should have same identity
        $this->assertEquals($created["id"], $updated["id"]);
        $this->assertEquals($updated["id"], $retrieved["id"]);

        // Final state should reflect the update
        $this->assertEquals($updateRequest->email, $retrieved["email"]);
        $this->assertEquals($updateRequest->external_applicant_name, $retrieved["external_applicant_name"]);
        
        if (isset($created["external_applicant_id"])) {
            $this->assertEquals($created["external_applicant_id"], $retrieved["external_applicant_id"]);
        }

        // CreatedOn should remain consistent
        $this->assertEquals($created["created_on"], $updated["created_on"]);
        $this->assertEquals($updated["created_on"], $retrieved["created_on"]);
    }

    private function validateBaseApplicantResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "email",
            "external_applicant_name",
            "created_on",
            "modified_on"
        );

        $this->assertStringStartsWith("aplt_", $response["id"]);

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
