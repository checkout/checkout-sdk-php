<?php

namespace Checkout\Tests\Identities\IdentityVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\Identities\Entities\ClientInformation;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAndOpenRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAttemptRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class IdentityVerificationIntegrationTest extends SandboxTestFixture
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
    public function shouldCreateIdentityVerificationAndAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildIdentityVerificationAndOpenRequest();

        $response = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAndAttempt($request);

        $this->validateCreatedIdentityVerificationAndAttempt($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdentityVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildIdentityVerificationRequest();

        $response = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($request);

        $this->validateCreatedIdentityVerification($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $response = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerification($createdResponse["id"]);

        $this->validateRetrievedIdentityVerification($response, $createdResponse);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeIdentityVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $response = $this->checkoutApi->getIdentityVerificationClient()->anonymizeIdentityVerification($createdResponse["id"]);

        $this->validateAnonymizedIdentityVerification($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdentityVerificationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $attemptRequest = $this->buildIdentityVerificationAttemptRequest();
        $response = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAttempt($createdResponse["id"], $attemptRequest);

        $this->validateCreatedIdentityVerificationAttempt($response, $attemptRequest);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttempts()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $attemptRequest = $this->buildIdentityVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAttempt($createdResponse["id"], $attemptRequest);

        $response = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttempts($createdResponse["id"]);

        $this->validateRetrievedIdentityVerificationAttempts($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $attemptRequest = $this->buildIdentityVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAttempt($createdResponse["id"], $attemptRequest);

        $response = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttempt($createdResponse["id"], $createdAttempt["id"]);

        $this->validateRetrievedIdentityVerificationAttempt($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationPdfReport()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $response = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationPdfReport($createdResponse["id"]);

        $this->validateIdentityVerificationPdfReport($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPerformCompleteIdentityVerificationWorkflow()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create Identity Verification and Attempt in one step
        $createAndAttemptRequest = $this->buildIdentityVerificationAndOpenRequest();
        $createdWithAttempt = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAndAttempt($createAndAttemptRequest);
        $this->validateCreatedIdentityVerificationAndAttempt($createdWithAttempt, $createAndAttemptRequest);

        // Get Identity Verification details
        $retrieved = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerification($createdWithAttempt["id"]);
        $this->validateRetrievedIdentityVerificationFromCreatedAndAttempt($retrieved, $createdWithAttempt);

        // Create additional attempt
        $attemptRequest = $this->buildIdentityVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAttempt($createdWithAttempt["id"], $attemptRequest);
        $this->validateCreatedIdentityVerificationAttempt($createdAttempt, $attemptRequest);

        // Get all attempts
        $attemptsResponse = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttempts($createdWithAttempt["id"]);
        $this->validateRetrievedIdentityVerificationAttempts($attemptsResponse, $createdAttempt);

        // Get specific attempt
        $retrievedAttempt = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttempt($createdWithAttempt["id"], $createdAttempt["id"]);
        $this->validateRetrievedIdentityVerificationAttempt($retrievedAttempt, $createdAttempt);

        // Get PDF report
        $reportResponse = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationPdfReport($createdWithAttempt["id"]);
        $this->validateIdentityVerificationPdfReport($reportResponse);

        // Anonymize Identity Verification
        $anonymized = $this->checkoutApi->getIdentityVerificationClient()->anonymizeIdentityVerification($createdWithAttempt["id"]);
        $this->validateAnonymizedIdentityVerification($anonymized);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPerformSeparateCreateAndAttemptWorkflow()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        // Create Identity Verification
        $createRequest = $this->buildIdentityVerificationRequest();
        $created = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);
        $this->validateCreatedIdentityVerification($created, $createRequest);

        // Get Identity Verification
        $retrieved = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerification($created["id"]);
        $this->validateRetrievedIdentityVerification($retrieved, $created);

        // Create attempt
        $attemptRequest = $this->buildIdentityVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAttempt($created["id"], $attemptRequest);
        $this->validateCreatedIdentityVerificationAttempt($createdAttempt, $attemptRequest);

        // Get attempts
        $attemptsResponse = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttempts($created["id"]);
        $this->validateRetrievedIdentityVerificationAttempts($attemptsResponse, $createdAttempt);

        // Get specific attempt
        $retrievedAttempt = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttempt($created["id"], $createdAttempt["id"]);
        $this->validateRetrievedIdentityVerificationAttempt($retrievedAttempt, $createdAttempt);

        // Get PDF report
        $reportResponse = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationPdfReport($created["id"]);
        $this->validateIdentityVerificationPdfReport($reportResponse);

        // Anonymize
        $anonymized = $this->checkoutApi->getIdentityVerificationClient()->anonymizeIdentityVerification($created["id"]);
        $this->validateAnonymizedIdentityVerification($anonymized);
    }

    // Request builders
    private function buildIdentityVerificationAndOpenRequest()
    {
        $declared_data = new DeclaredData();
        $declared_data->name = "John Doe " . $this->generateRandomString();

        $request = new IdentityVerificationAndOpenRequest();
        $request->declared_data = $declared_data;
        $request->redirect_url = "https://example.com/success?session=" . $this->generateRandomString();
        $request->user_journey_id = "uj_" . $this->generateRandomString();
        $request->applicant_id = "applicant_" . $this->generateRandomString();

        return $request;
    }

    private function buildIdentityVerificationRequest()
    {
        $declared_data = new DeclaredData();
        $declared_data->name = "Jane Smith " . $this->generateRandomString();

        $request = new IdentityVerificationRequest();
        $request->applicant_id = "applicant_" . $this->generateRandomString();
        $request->declared_data = $declared_data;
        $request->user_journey_id = "uj_" . $this->generateRandomString();

        return $request;
    }

    private function buildIdentityVerificationAttemptRequest()
    {
        $client_information = new ClientInformation();
        $client_information->pre_selected_residence_country = "GB";
        $client_information->pre_selected_language = "en";

        $request = new IdentityVerificationAttemptRequest();
        $request->redirect_url = "https://example.com/success?session=" . $this->generateRandomString();
        $request->client_information = $client_information;

        return $request;
    }

    // Response validators
    private function validateCreatedIdentityVerificationAndAttempt($response, $request)
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["attempt_id"]);
        
        if (isset($response["declared_data"]) && isset($request->declared_data)) {
            $this->assertEquals($request->declared_data->name, $response["declared_data"]["name"]);
        }
        
        $this->assertNotNull($response["status"]);
        if (isset($response["redirect_url"])) {
            $this->assertNotNull($response["redirect_url"]);
        }
    }

    private function validateCreatedIdentityVerification($response, $request)
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        
        if (isset($response["applicant_id"])) {
            $this->assertEquals($request->applicant_id, $response["applicant_id"]);
        }
        
        if (isset($response["declared_data"]) && isset($request->declared_data)) {
            $this->assertEquals($request->declared_data->name, $response["declared_data"]["name"]);
        }
        
        $this->assertNotNull($response["status"]);
        if (isset($response["created_on"])) {
            $this->assertNotNull($response["created_on"]);
        }
    }

    private function validateRetrievedIdentityVerification($response, $createdResponse)
    {
        $this->assertNotNull($response);
        $this->assertEquals($createdResponse["id"], $response["id"]);
        $this->assertEquals($createdResponse["status"], $response["status"]);
        
        if (isset($createdResponse["applicant_id"])) {
            $this->assertEquals($createdResponse["applicant_id"], $response["applicant_id"]);
        }
    }

    private function validateRetrievedIdentityVerificationFromCreatedAndAttempt($response, $createdResponse)
    {
        $this->assertNotNull($response);
        $this->assertEquals($createdResponse["id"], $response["id"]);
        $this->assertNotNull($response["status"]);
        
        if (isset($createdResponse["declared_data"])) {
            $this->assertEquals($createdResponse["declared_data"]["name"], $response["declared_data"]["name"]);
        }
    }

    private function validateAnonymizedIdentityVerification($response)
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        $this->assertEquals("anonymized", $response["status"]);
    }

    private function validateCreatedIdentityVerificationAttempt($response, $request)
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["status"]);
        
        if (isset($response["redirect_url"])) {
            $this->assertNotNull($response["redirect_url"]);
        }
    }

    private function validateRetrievedIdentityVerificationAttempts($response, $createdAttempt)
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response["count"]);
        $this->assertTrue(is_array($response["attempts"]));
        $this->assertGreaterThanOrEqual(1, $response["count"]);
        
        // Find the created attempt in the list
        $found = false;
        foreach ($response["attempts"] as $attempt) {
            if ($attempt["id"] === $createdAttempt["id"]) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }

    private function validateRetrievedIdentityVerificationAttempt($response, $createdAttempt)
    {
        $this->assertNotNull($response);
        $this->assertEquals($createdAttempt["id"], $response["id"]);
        $this->assertEquals($createdAttempt["status"], $response["status"]);
        
        if (isset($createdAttempt["redirect_url"])) {
            $this->assertEquals($createdAttempt["redirect_url"], $response["redirect_url"]);
        }
    }

    private function validateIdentityVerificationPdfReport($response)
    {
        $this->assertNotNull($response);
        $this->assertNotNull($response["id"]);
        
        if (isset($response["pdf_data"])) {
            $this->assertNotNull($response["pdf_data"]);
        }
        
        if (isset($response["report_url"])) {
            $this->assertNotNull($response["report_url"]);
        }
    }

    private function generateRandomString()
    {
        return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz"), 0, 8);
    }
}
