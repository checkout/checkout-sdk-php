<?php

namespace Checkout\Tests\Identities\AddressDocumentVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationRequest;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationAttemptRequest;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class AddressDocumentVerificationIntegrationTest extends SandboxTestFixture
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
    public function shouldCreateAddressDocumentVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildAddressDocumentVerificationRequest();

        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($request);

        $this->validateCreatedAddressDocumentVerification($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildAddressDocumentVerificationRequest();
        $created = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($request);

        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->getAddressDocumentVerification($created["id"]);

        $this->validateRetrievedAddressDocumentVerification($response, $created);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeAddressDocumentVerification()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildAddressDocumentVerificationRequest();
        $created = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($request);

        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->anonymizeAddressDocumentVerification($created["id"]);

        $this->validateBaseAddressDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAddressDocumentVerificationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $verificationRequest = $this->buildAddressDocumentVerificationRequest();
        $created = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($verificationRequest);

        $attemptRequest = $this->buildAddressDocumentVerificationAttemptRequest();
        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerificationAttempt($created["id"], $attemptRequest);

        $this->validateBaseAddressDocumentVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerificationAttempts()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $verificationRequest = $this->buildAddressDocumentVerificationRequest();
        $created = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($verificationRequest);

        $attemptRequest = $this->buildAddressDocumentVerificationAttemptRequest();
        $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerificationAttempt($created["id"], $attemptRequest);

        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->getAddressDocumentVerificationAttempts($created["id"]);

        $this->validateRetrievedAddressDocumentVerificationAttempts($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerificationAttempt()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $verificationRequest = $this->buildAddressDocumentVerificationRequest();
        $created = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($verificationRequest);

        $attemptRequest = $this->buildAddressDocumentVerificationAttemptRequest();
        $createdAttempt = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerificationAttempt($created["id"], $attemptRequest);

        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->getAddressDocumentVerificationAttempt($created["id"], $createdAttempt["id"]);

        $this->validateRetrievedAddressDocumentVerificationAttempt($response, $createdAttempt);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerificationReport()
    {
        $this->markTestSkipped("This test requires valid test environment setup");

        $request = $this->buildAddressDocumentVerificationRequest();
        $created = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->createAddressDocumentVerification($request);

        $response = $this->checkoutApi->getAddressDocumentVerificationClient()
            ->getAddressDocumentVerificationReport($created["id"]);

        $this->assertResponse($response, "id");
    }

    private function buildAddressDocumentVerificationRequest(): AddressDocumentVerificationRequest
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "Hannah Bret";

        $request = new AddressDocumentVerificationRequest();
        $request->applicant_id = $this->getEnvironmentVariable("CHECKOUT_TEST_APPLICANT_ID", "aplt_test_applicant_id");
        $request->user_journey_id = "usj_" . uniqid();
        $request->declared_data = $declaredData;

        return $request;
    }

    private function buildAddressDocumentVerificationAttemptRequest(): AddressDocumentVerificationAttemptRequest
    {
        $request = new AddressDocumentVerificationAttemptRequest();
        $request->document = "base64-encoded-document-image-data";

        return $request;
    }

    private function getEnvironmentVariable(string $key, string $defaultValue): string
    {
        return getenv($key) ?: $defaultValue;
    }

    private function validateCreatedAddressDocumentVerification(
        array $response,
        AddressDocumentVerificationRequest $request
    ): void {
        $this->validateBaseAddressDocumentVerificationResponse($response);

        $this->assertEquals($request->applicant_id, $response["applicant_id"]);
        $this->assertEquals($request->user_journey_id, $response["user_journey_id"]);

        if (isset($response["declared_data"]) && isset($request->declared_data)) {
            $this->assertEquals($request->declared_data->name, $response["declared_data"]["name"]);
        }

        $this->assertContains($response["status"], [
            "created",
            "quality_checks_in_progress",
            "checks_in_progress",
            "approved",
            "declined",
            "retry_required",
            "inconclusive",
        ]);
    }

    private function validateRetrievedAddressDocumentVerification(array $retrieved, array $original): void
    {
        $this->validateBaseAddressDocumentVerificationResponse($retrieved);

        $this->assertEquals($original["id"], $retrieved["id"]);
        $this->assertEquals($original["applicant_id"], $retrieved["applicant_id"]);
        $this->assertEquals($original["user_journey_id"], $retrieved["user_journey_id"]);
    }

    private function validateRetrievedAddressDocumentVerificationAttempts(array $response): void
    {
        $this->assertResponse($response, "total_count", "skip", "limit", "data", "_links");

        $this->assertTrue(is_array($response["data"]));

        if (!empty($response["data"])) {
            $this->validateBaseAddressDocumentVerificationAttemptResponse($response["data"][0]);
        }
    }

    private function validateRetrievedAddressDocumentVerificationAttempt(array $retrieved, array $created): void
    {
        $this->validateBaseAddressDocumentVerificationAttemptResponse($retrieved);
        $this->assertEquals($created["id"], $retrieved["id"]);
    }

    private function validateBaseAddressDocumentVerificationResponse(array $response): void
    {
        $this->assertResponse(
            $response,
            "id",
            "applicant_id",
            "user_journey_id",
            "status",
            "response_codes",
            "_links"
        );

        $this->assertTrue(strpos($response["id"] ?? "", "adv_") === 0, "ID should start with 'adv_'");
        $this->assertTrue(
            strpos($response["applicant_id"] ?? "", "aplt_") === 0,
            "Applicant ID should start with 'aplt_'"
        );

        if (isset($response["created_on"])) {
            $this->assertLessThanOrEqual(time(), strtotime($response["created_on"]));
        }
        if (isset($response["modified_on"]) && isset($response["created_on"])) {
            $this->assertGreaterThanOrEqual(
                strtotime($response["created_on"]),
                strtotime($response["modified_on"])
            );
        }
    }

    private function validateBaseAddressDocumentVerificationAttemptResponse(array $response): void
    {
        $this->assertResponse($response, "id", "status", "response_codes", "_links");

        $this->assertTrue(strpos($response["id"] ?? "", "adva_") === 0, "Attempt ID should start with 'adva_'");

        if (isset($response["created_on"])) {
            $this->assertLessThanOrEqual(time(), strtotime($response["created_on"]));
        }
    }
}
