<?php

namespace Checkout\Tests\Identities\FaceAuthentication;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\ClientInformation;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationRequest;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationAttemptRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class FaceAuthenticationIntegrationTest extends SandboxTestFixture
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
    public function shouldCreateFaceAuthentication()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildFaceAuthenticationRequest();

        $response = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($request);

        $this->validateCreatedFaceAuthentication($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthentication()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create face authentication first
        $request = $this->buildFaceAuthenticationRequest();
        $createdFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($request);

        // Get the created face authentication
        $response = $this->checkoutApi->getFaceAuthenticationClient()
            ->getFaceAuthentication($createdFaceAuthentication["id"]);

        $this->validateRetrievedFaceAuthentication($response, $createdFaceAuthentication);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeFaceAuthentication()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create face authentication first
        $request = $this->buildFaceAuthenticationRequest();
        $createdFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($request);

        // Anonymize the face authentication
        $response = $this->checkoutApi->getFaceAuthenticationClient()
            ->anonymizeFaceAuthentication($createdFaceAuthentication["id"]);

        $this->validateAnonymizedFaceAuthentication($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateFaceAuthenticationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create face authentication first
        $faceAuthRequest = $this->buildFaceAuthenticationRequest();
        $createdFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($faceAuthRequest);

        // Create attempt
        $attemptRequest = $this->buildFaceAuthenticationAttemptRequest();
        $response = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthenticationAttempt($createdFaceAuthentication["id"], $attemptRequest);

        $this->validateCreatedFaceAuthenticationAttempt($response, $attemptRequest);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthenticationAttempts()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create face authentication first
        $faceAuthRequest = $this->buildFaceAuthenticationRequest();
        $createdFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($faceAuthRequest);

        // Create attempt first
        $attemptRequest = $this->buildFaceAuthenticationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthenticationAttempt($createdFaceAuthentication["id"], $attemptRequest);

        // Get attempts
        $response = $this->checkoutApi->getFaceAuthenticationClient()
            ->getFaceAuthenticationAttempts($createdFaceAuthentication["id"]);

        $this->validateRetrievedFaceAuthenticationAttempts($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthenticationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create face authentication first
        $faceAuthRequest = $this->buildFaceAuthenticationRequest();
        $createdFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($faceAuthRequest);

        // Create attempt first
        $attemptRequest = $this->buildFaceAuthenticationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthenticationAttempt($createdFaceAuthentication["id"], $attemptRequest);

        // Get single attempt
        $response = $this->checkoutApi->getFaceAuthenticationClient()
            ->getFaceAuthenticationAttempt($createdFaceAuthentication["id"], $createdAttempt["id"]);

        $this->validateRetrievedFaceAuthenticationAttempt($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPerformFaceAuthenticationWorkflow()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create face authentication
        $faceAuthRequest = $this->buildFaceAuthenticationRequest();
        $createdFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthentication($faceAuthRequest);

        // Retrieve face authentication
        $retrievedFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->getFaceAuthentication($createdFaceAuthentication["id"]);

        // Create attempt
        $attemptRequest = $this->buildFaceAuthenticationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getFaceAuthenticationClient()
            ->createFaceAuthenticationAttempt($createdFaceAuthentication["id"], $attemptRequest);

        // Get attempts
        $attemptsResponse = $this->checkoutApi->getFaceAuthenticationClient()
            ->getFaceAuthenticationAttempts($createdFaceAuthentication["id"]);

        // Get single attempt
        $retrievedAttempt = $this->checkoutApi->getFaceAuthenticationClient()
            ->getFaceAuthenticationAttempt($createdFaceAuthentication["id"], $createdAttempt["id"]);

        // Anonymize
        $anonymizedFaceAuthentication = $this->checkoutApi->getFaceAuthenticationClient()
            ->anonymizeFaceAuthentication($createdFaceAuthentication["id"]);

        $this->validateWorkflowProgression($createdFaceAuthentication, $retrievedFaceAuthentication, $createdAttempt, $attemptsResponse, $retrievedAttempt, $anonymizedFaceAuthentication);
    }

    private function buildFaceAuthenticationRequest(): FaceAuthenticationRequest
    {
        $request = new FaceAuthenticationRequest();
        $request->applicant_id = $this->getEnvironmentVariable("CHECKOUT_TEST_APPLICANT_ID", "aplt_test_applicant_id");
        $request->user_journey_id = "journey_test_" . uniqid();

        return $request;
    }

    private function buildFaceAuthenticationAttemptRequest(): FaceAuthenticationAttemptRequest
    {
        $clientInformation = new ClientInformation();
        $clientInformation->pre_selected_residence_country = "US";
        $clientInformation->pre_selected_language = "en-US";

        $request = new FaceAuthenticationAttemptRequest();
        $request->redirect_url = "https://example.com/redirect";
        $request->client_information = $clientInformation;

        return $request;
    }

    private function getEnvironmentVariable(string $key, string $defaultValue): string
    {
        return getenv($key) ?: $defaultValue;
    }

    private function validateCreatedFaceAuthentication(array $response, FaceAuthenticationRequest $request): void
    {
        // Base validation
        $this->validateBaseFaceAuthenticationResponse($response);

        // Request-specific validation
        $this->assertEquals($request->applicant_id, $response["applicant_id"]);
        $this->assertEquals($request->user_journey_id, $response["user_journey_id"]);

        // Status should be initial state
        $this->assertContains($response["status"], ["created", "pending"]);
    }

    private function validateRetrievedFaceAuthentication(array $retrieved, array $original): void
    {
        // Base validation
        $this->validateBaseFaceAuthenticationResponse($retrieved);

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

    private function validateAnonymizedFaceAuthentication(array $response): void
    {
        // Base validation
        $this->validateBaseFaceAuthenticationResponse($response);
    }

    private function validateCreatedFaceAuthenticationAttempt(array $response, FaceAuthenticationAttemptRequest $request): void
    {
        // Base validation
        $this->validateBaseFaceAuthenticationAttemptResponse($response);

        // Request-specific validation
        if (isset($response["redirect_url"])) {
            $this->assertEquals($request->redirect_url, $response["redirect_url"]);
        }
    }

    private function validateRetrievedFaceAuthenticationAttempts(array $response, array $createdAttempt): void
    {
        $this->assertResponse(
            $response,
            "attempts",
            "total_count"
        );

        $this->assertTrue(is_array($response["attempts"]));
        $this->assertGreaterThan(0, $response["total_count"]);
        
        if (!empty($response["attempts"])) {
            $this->validateBaseFaceAuthenticationAttemptResponse($response["attempts"][0]);
        }
    }

    private function validateRetrievedFaceAuthenticationAttempt(array $retrieved, array $created): void
    {
        // Base validation
        $this->validateBaseFaceAuthenticationAttemptResponse($retrieved);

        // Identity validation
        $this->assertEquals($created["id"], $retrieved["id"]);
    }

    private function validateWorkflowProgression(
        array $created,
        array $retrieved,
        array $createdAttempt,
        array $attemptsResponse,
        array $retrievedAttempt,
        array $anonymized
    ): void {
        // All face authentications should have same identity
        $this->assertEquals($created["id"], $retrieved["id"]);
        $this->assertEquals($created["id"], $anonymized["id"]);

        // Attempt should be linked to face authentication
        $this->assertEquals($created["id"], $createdAttempt["face_authentication_id"]);

        // Attempts response should contain the created attempt
        $this->assertGreaterThan(0, $attemptsResponse["total_count"]);
        $this->assertTrue(is_array($attemptsResponse["attempts"]));

        // Retrieved attempt should match created attempt
        $this->assertEquals($createdAttempt["id"], $retrievedAttempt["id"]);
    }

    private function validateBaseFaceAuthenticationResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "applicant_id",
            "user_journey_id",
            "status",
            "created_on"
        );

        $this->assertTrue(strpos($response["id"] ?? "", "face_auth_") === 0, "Face auth ID should start with 'face_auth_'");
        $this->assertTrue(strpos($response["applicant_id"] ?? "", "aplt_") === 0, "Applicant ID should start with 'aplt_'");

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

    private function validateBaseFaceAuthenticationAttemptResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "status",
            "created_on"
        );

        if (isset($response["face_authentication_id"])) {
            $this->assertTrue(strpos($response["face_authentication_id"], "face_auth_") === 0, "Face authentication ID should start with 'face_auth_'");
        }

        // Validate timestamps
        if (isset($response["created_on"])) {
            $this->assertLessThanOrEqual(time(), strtotime($response["created_on"]));
        }
    }
}
