<?php

namespace Checkout\Tests\Identities\IdDocumentVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\Identities\IdDocumentVerification\Requests\IdDocumentVerificationRequest;
use Checkout\Identities\IdDocumentVerification\Requests\IdDocumentVerificationAttemptRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class IdDocumentVerificationIntegrationTest extends SandboxTestFixture
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
    public function shouldCreateIdDocumentVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildIdDocumentVerificationRequest();

        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($request);

        $this->validateCreatedIdDocumentVerification($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification first
        $request = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($request);

        // Get the created ID document verification
        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerification($createdIdDocumentVerification["id"]);

        $this->validateRetrievedIdDocumentVerification($response, $createdIdDocumentVerification);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeIdDocumentVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification first
        $request = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($request);

        // Anonymize the ID document verification
        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->anonymizeIdDocumentVerification($createdIdDocumentVerification["id"]);

        $this->validateAnonymizedIdDocumentVerification($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdDocumentVerificationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification first
        $idDocVerificationRequest = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($idDocVerificationRequest);

        // Create attempt
        $attemptRequest = $this->buildIdDocumentVerificationAttemptRequest();
        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerificationAttempt($createdIdDocumentVerification["id"], $attemptRequest);

        $this->validateCreatedIdDocumentVerificationAttempt($response, $attemptRequest);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerificationAttempts()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification first
        $idDocVerificationRequest = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($idDocVerificationRequest);

        // Create attempt first
        $attemptRequest = $this->buildIdDocumentVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerificationAttempt($createdIdDocumentVerification["id"], $attemptRequest);

        // Get attempts
        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerificationAttempts($createdIdDocumentVerification["id"]);

        $this->validateRetrievedIdDocumentVerificationAttempts($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerificationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification first
        $idDocVerificationRequest = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($idDocVerificationRequest);

        // Create attempt first
        $attemptRequest = $this->buildIdDocumentVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerificationAttempt($createdIdDocumentVerification["id"], $attemptRequest);

        // Get single attempt
        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerificationAttempt($createdIdDocumentVerification["id"], $createdAttempt["id"]);

        $this->validateRetrievedIdDocumentVerificationAttempt($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerificationReport()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification first
        $request = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($request);

        // Get report
        $response = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerificationReport($createdIdDocumentVerification["id"]);

        $this->validateIdDocumentVerificationReport($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPerformIdDocumentVerificationWorkflow()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create ID document verification
        $idDocVerificationRequest = $this->buildIdDocumentVerificationRequest();
        $createdIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerification($idDocVerificationRequest);

        // Retrieve ID document verification
        $retrievedIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerification($createdIdDocumentVerification["id"]);

        // Create attempt
        $attemptRequest = $this->buildIdDocumentVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdDocumentVerificationClient()
            ->createIdDocumentVerificationAttempt($createdIdDocumentVerification["id"], $attemptRequest);

        // Get attempts
        $attemptsResponse = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerificationAttempts($createdIdDocumentVerification["id"]);

        // Get single attempt
        $retrievedAttempt = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerificationAttempt($createdIdDocumentVerification["id"], $createdAttempt["id"]);

        // Get report
        $reportResponse = $this->checkoutApi->getIdDocumentVerificationClient()
            ->getIdDocumentVerificationReport($createdIdDocumentVerification["id"]);

        // Anonymize
        $anonymizedIdDocumentVerification = $this->checkoutApi->getIdDocumentVerificationClient()
            ->anonymizeIdDocumentVerification($createdIdDocumentVerification["id"]);

        $this->validateWorkflowProgression($createdIdDocumentVerification, $retrievedIdDocumentVerification, $createdAttempt, $attemptsResponse, $retrievedAttempt, $reportResponse, $anonymizedIdDocumentVerification);
    }

    private function buildIdDocumentVerificationRequest(): IdDocumentVerificationRequest
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "John Doe";

        $request = new IdDocumentVerificationRequest();
        $request->applicant_id = $this->getEnvironmentVariable("CHECKOUT_TEST_APPLICANT_ID", "aplt_test_applicant_id");
        $request->user_journey_id = "journey_test_" . uniqid();
        $request->declared_data = $declaredData;

        return $request;
    }

    private function buildIdDocumentVerificationAttemptRequest(): IdDocumentVerificationAttemptRequest
    {
        $request = new IdDocumentVerificationAttemptRequest();
        $request->document_front = "base64-encoded-front-image-data";
        $request->document_back = "base64-encoded-back-image-data";

        return $request;
    }

    private function getEnvironmentVariable(string $key, string $defaultValue): string
    {
        return getenv($key) ?: $defaultValue;
    }

    private function validateCreatedIdDocumentVerification(array $response, IdDocumentVerificationRequest $request): void
    {
        // Base validation
        $this->validateBaseIdDocumentVerificationResponse($response);

        // Request-specific validation
        $this->assertEquals($request->applicant_id, $response["applicant_id"]);
        $this->assertEquals($request->user_journey_id, $response["user_journey_id"]);

        if (isset($response["declared_data"]) && isset($request->declared_data)) {
            $this->assertEquals($request->declared_data->name, $response["declared_data"]["name"]);
        }

        // Status should be initial state
        $this->assertContains($response["status"], ["created", "pending"]);
    }

    private function validateRetrievedIdDocumentVerification(array $retrieved, array $original): void
    {
        // Base validation
        $this->validateBaseIdDocumentVerificationResponse($retrieved);

        // Identity validation
        $this->assertEquals($original["id"], $retrieved["id"]);
        $this->assertEquals($original["applicant_id"], $retrieved["applicant_id"]);
        $this->assertEquals($original["user_journey_id"], $retrieved["user_journey_id"]);

        // Timestamps should be consistent or updated
        $this->assertEquals($original["created_on"], $retrieved["created_on"]);
        if (isset($retrieved["modified_on"]) && isset($original["modified_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($original["modified_on"]),
                strtotime($retrieved["modified_on"])
            );
        }
    }

    private function validateAnonymizedIdDocumentVerification(array $response): void
    {
        // Base validation
        $this->validateBaseIdDocumentVerificationResponse($response);
    }

    private function validateCreatedIdDocumentVerificationAttempt(array $response, IdDocumentVerificationAttemptRequest $request): void
    {
        // Base validation
        $this->validateBaseIdDocumentVerificationAttemptResponse($response);

        // Request-specific validation would go here if the response included the document data
    }

    private function validateRetrievedIdDocumentVerificationAttempts(array $response, array $createdAttempt): void
    {
        $this->assertResponse(
            $response,
            "data",
            "total_count"
        );

        $this->assertTrue(is_array($response["data"]));
        $this->assertGreaterThan(0, $response["total_count"]);
        
        if (!empty($response["data"])) {
            $this->validateBaseIdDocumentVerificationAttemptResponse($response["data"][0]);
        }
    }

    private function validateRetrievedIdDocumentVerificationAttempt(array $retrieved, array $created): void
    {
        // Base validation
        $this->validateBaseIdDocumentVerificationAttemptResponse($retrieved);

        // Identity validation
        $this->assertEquals($created["id"], $retrieved["id"]);
    }

    private function validateIdDocumentVerificationReport(array $response): void
    {
        $this->assertResponse(
            $response,
            "id"
        );

        // PDF URL might be present in various formats
        if (isset($response["pdf_url"])) {
            $this->assertNotNull($response["pdf_url"]);
        }
    }

    private function validateWorkflowProgression(
        array $created,
        array $retrieved,
        array $createdAttempt,
        array $attemptsResponse,
        array $retrievedAttempt,
        array $reportResponse,
        array $anonymized
    ): void {
        // All ID document verifications should have same identity
        $this->assertEquals($created["id"], $retrieved["id"]);
        $this->assertEquals($created["id"], $anonymized["id"]);

        // Attempt should be linked to ID document verification
        $this->assertEquals($created["id"], $createdAttempt["id_document_verification_id"]);

        // Attempts response should contain the created attempt
        $this->assertGreaterThan(0, $attemptsResponse["total_count"]);
        $this->assertTrue(is_array($attemptsResponse["data"]));

        // Retrieved attempt should match created attempt
        $this->assertEquals($createdAttempt["id"], $retrievedAttempt["id"]);

        // Report should be for the same ID document verification
        $this->assertEquals($created["id"], $reportResponse["id"]);
    }

    private function validateBaseIdDocumentVerificationResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "applicant_id",
            "user_journey_id",
            "status",
            "created_on"
        );

        $this->assertStringStartsWith("iddoc_", $response["id"] ?? "");
        $this->assertStringStartsWith("aplt_", $response["applicant_id"] ?? "");

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

    private function validateBaseIdDocumentVerificationAttemptResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "status",
            "created_on"
        );

        if (isset($response["id_document_verification_id"])) {
            $this->assertStringStartsWith("iddoc_", $response["id_document_verification_id"]);
        }

        // Validate timestamps
        if (isset($response["created_on"])) {
            $this->assertLessThanOrEqual(time(), strtotime($response["created_on"]));
        }
    }
}
