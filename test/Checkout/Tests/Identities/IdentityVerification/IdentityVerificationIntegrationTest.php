<?php

namespace Checkout\Tests\Identities\IdentityVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\AttemptsQueryFilter;
use Checkout\Identities\Entities\IdentityDeclaredData;
use Checkout\Identities\Entities\IdentityVerificationClientInformation;
use Checkout\Identities\Entities\IdvAddress;
use Checkout\Identities\Entities\PhoneNumber;
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
    public function shouldGetIdentityVerificationAttemptAssets()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $createRequest = $this->buildIdentityVerificationRequest();
        $createdResponse = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerification($createRequest);

        $attemptRequest = $this->buildIdentityVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getIdentityVerificationClient()->createIdentityVerificationAttempt($createdResponse["id"], $attemptRequest);

        $query = new \Checkout\Identities\Entities\AttemptAssetsQueryFilter();
        $query->skip = 0;
        $query->limit = 10;
        $response = $this->checkoutApi->getIdentityVerificationClient()->getIdentityVerificationAttemptAssets($createdResponse["id"], $createdAttempt["id"], $query);

        $this->assertNotNull($response);
        $this->assertArrayHasKey("data", $response);
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
    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttemptsWithPagination()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $created = $this->checkoutApi->getIdentityVerificationClient()
            ->createIdentityVerificationAndAttempt($this->buildIdentityVerificationAndOpenRequest());

        $query = new AttemptsQueryFilter();
        $query->skip = 0;
        $query->limit = 1;

        $response = $this->checkoutApi->getIdentityVerificationClient()
            ->getIdentityVerificationAttempts($created["id"], $query);

        $this->assertNotNull($response);
        $this->assertLessThanOrEqual(1, count($response["data"]));
        $this->assertEquals(1, $response["limit"]);
        $this->assertEquals(0, $response["skip"]);
    }

    private function buildIdentityVerificationAndOpenRequest()
    {
        $declared_data = new IdentityDeclaredData();
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
        $address = new IdvAddress();
        $address->address_line1 = "123 Main Street";
        $address->city = "London";
        $address->zip = "SW1A 1AA";
        $address->country = "GB";

        $phone_number = new PhoneNumber();
        $phone_number->country_code = "+44";
        $phone_number->number = "7700900000";

        $declared_data = new IdentityDeclaredData();
        $declared_data->name = "Jane Smith " . $this->generateRandomString();
        $declared_data->birth_date = "1994-10-15";
        $declared_data->email = "jane.smith@example.com";
        $declared_data->phone_number = $phone_number;
        $declared_data->address = $address;

        $request = new IdentityVerificationRequest();
        $request->applicant_id = "applicant_" . $this->generateRandomString();
        $request->declared_data = $declared_data;
        $request->user_journey_id = "uj_" . $this->generateRandomString();

        return $request;
    }

    private function buildIdentityVerificationAttemptRequest()
    {
        $client_information = new IdentityVerificationClientInformation();
        $client_information->pre_selected_residence_country = "GB";
        $client_information->pre_selected_language = "en";
        $client_information->pre_selected_document_issuing_country = "GB";
        $client_information->pre_selected_document_type = "Passport";

        $phone_number = new PhoneNumber();
        $phone_number->country_code = "+44";
        $phone_number->number = "7700900000";

        $request = new IdentityVerificationAttemptRequest();
        $request->redirect_url = "https://example.com/success?session=" . $this->generateRandomString();
        $request->phone_number = $phone_number;
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
        $this->assertNotNull($response["total_count"]);
        $this->assertArrayHasKey("skip", $response);
        $this->assertArrayHasKey("limit", $response);
        $this->assertTrue(is_array($response["data"]));
        $this->assertGreaterThanOrEqual(1, $response["total_count"]);

        // Find the created attempt in the list
        $found = false;
        foreach ($response["data"] as $attempt) {
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
