<?php

namespace Checkout\Tests\Identities\IdentityVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\Identities\Entities\ClientInformation;
use Checkout\Identities\IdentityVerification\IdentityVerificationClient;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAndOpenRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAttemptRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class IdentityVerificationClientTest extends UnitTestFixture
{
    /**
     * @var IdentityVerificationClient
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
        $this->client = new IdentityVerificationClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdentityVerificationAndAttempt()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationAndAttemptResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildIdentityVerificationAndOpenRequest();
        $response = $this->client->createIdentityVerificationAndAttempt($request);

        $this->assertNotNull($response);
        $this->validateIdentityVerificationAndAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateIdentityVerificationAndAttempt()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationAndAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("create-and-open-idv")
            ->willReturn($expectedResponse);

        $request = $this->buildIdentityVerificationAndOpenRequest();
        $response = $this->client->createIdentityVerificationAndAttempt($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdentityVerification()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildIdentityVerificationRequest();
        $response = $this->client->createIdentityVerification($request);

        $this->assertNotNull($response);
        $this->validateIdentityVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateIdentityVerification()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("identity-verifications")
            ->willReturn($expectedResponse);

        $request = $this->buildIdentityVerificationRequest();
        $response = $this->client->createIdentityVerification($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerification()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerification("idv_test123");

        $this->assertNotNull($response);
        $this->validateIdentityVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdentityVerification()
    {
        $identityVerificationId = "idv_test123";
        $expectedResponse = $this->buildExpectedIdentityVerificationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("identity-verifications/" . $identityVerificationId)
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerification($identityVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeIdentityVerification()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeIdentityVerification("idv_test123");

        $this->assertNotNull($response);
        $this->validateIdentityVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForAnonymizeIdentityVerification()
    {
        $identityVerificationId = "idv_test123";
        $expectedResponse = $this->buildExpectedIdentityVerificationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("identity-verifications/" . $identityVerificationId . "/anonymize")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeIdentityVerification($identityVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdentityVerificationAttempt()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildIdentityVerificationAttemptRequest();
        $response = $this->client->createIdentityVerificationAttempt("idv_test123", $request);

        $this->assertNotNull($response);
        $this->validateIdentityVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateIdentityVerificationAttempt()
    {
        $identityVerificationId = "idv_test123";
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("identity-verifications/" . $identityVerificationId . "/attempts")
            ->willReturn($expectedResponse);

        $request = $this->buildIdentityVerificationAttemptRequest();
        $response = $this->client->createIdentityVerificationAttempt($identityVerificationId, $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttempts()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptsResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationAttempts("idv_test123");

        $this->assertNotNull($response);
        $this->validateIdentityVerificationAttemptsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdentityVerificationAttempts()
    {
        $identityVerificationId = "idv_test123";
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptsResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("identity-verifications/" . $identityVerificationId . "/attempts")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationAttempts($identityVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttempt()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationAttempt("idv_test123", "att_test456");

        $this->assertNotNull($response);
        $this->validateIdentityVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdentityVerificationAttempt()
    {
        $identityVerificationId = "idv_test123";
        $attemptId = "att_test456";
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("identity-verifications/" . $identityVerificationId . "/attempts/" . $attemptId)
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationAttempt($identityVerificationId, $attemptId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationPdfReport()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationPdfReportResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationPdfReport("idv_test123");

        $this->assertNotNull($response);
        $this->validateIdentityVerificationPdfReportResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdentityVerificationPdfReport()
    {
        $identityVerificationId = "idv_test123";
        $expectedResponse = $this->buildExpectedIdentityVerificationPdfReportResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("identity-verifications/" . $identityVerificationId . "/pdf-report")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationPdfReport($identityVerificationId);

        $this->assertNotNull($response);
    }

    // Request builders
    private function buildIdentityVerificationAndOpenRequest()
    {
        $declared_data = new DeclaredData();
        $declared_data->name = "John Doe";

        $request = new IdentityVerificationAndOpenRequest();
        $request->declared_data = $declared_data;
        $request->redirect_url = "https://example.com/success";
        $request->user_journey_id = "uj_test789";
        $request->applicant_id = "applicant_test123";

        return $request;
    }

    private function buildIdentityVerificationRequest()
    {
        $declared_data = new DeclaredData();
        $declared_data->name = "Jane Smith";

        $request = new IdentityVerificationRequest();
        $request->applicant_id = "applicant_test456";
        $request->declared_data = $declared_data;
        $request->user_journey_id = "uj_test789";

        return $request;
    }

    private function buildIdentityVerificationAttemptRequest()
    {
        $client_information = new ClientInformation();
        $client_information->pre_selected_residence_country = "GB";
        $client_information->pre_selected_language = "en";

        $request = new IdentityVerificationAttemptRequest();
        $request->redirect_url = "https://example.com/success";
        $request->client_information = $client_information;

        return $request;
    }

    // Response builders
    private function buildExpectedIdentityVerificationAndAttemptResponse()
    {
        return [
            "id" => "idv_test123",
            "attempt_id" => "att_test456",
            "declared_data" => [
                "name" => "John Doe"
            ],
            "redirect_url" => "https://checkout.example.com/sessions/idv_test123",
            "status" => "pending",
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/identity-verifications/idv_test123"
                ],
                "attempt" => [
                    "href" => "https://api.checkout.com/identity-verifications/idv_test123/attempts/att_test456"
                ]
            ]
        ];
    }

    private function buildExpectedIdentityVerificationResponse()
    {
        return [
            "id" => "idv_test123",
            "applicant_id" => "applicant_test456",
            "declared_data" => [
                "name" => "Jane Smith"
            ],
            "user_journey_id" => "uj_test789",
            "status" => "pending",
            "created_on" => "2023-03-15T10:30:00Z",
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/identity-verifications/idv_test123"
                ]
            ]
        ];
    }

    private function buildExpectedIdentityVerificationAttemptResponse()
    {
        return [
            "id" => "att_test456",
            "identity_verification_id" => "idv_test123",
            "redirect_url" => "https://checkout.example.com/sessions/att_test456",
            "status" => "pending",
            "created_on" => "2023-03-15T10:35:00Z",
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/identity-verifications/idv_test123/attempts/att_test456"
                ]
            ]
        ];
    }

    private function buildExpectedIdentityVerificationAttemptsResponse()
    {
        return [
            "count" => 2,
            "attempts" => [
                [
                    "id" => "att_test456",
                    "identity_verification_id" => "idv_test123",
                    "status" => "pending",
                    "created_on" => "2023-03-15T10:35:00Z"
                ],
                [
                    "id" => "att_test789",
                    "identity_verification_id" => "idv_test123",
                    "status" => "completed",
                    "created_on" => "2023-03-15T11:00:00Z"
                ]
            ],
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/identity-verifications/idv_test123/attempts"
                ]
            ]
        ];
    }

    private function buildExpectedIdentityVerificationPdfReportResponse()
    {
        return [
            "id" => "idv_test123",
            "report_url" => "https://api.checkout.com/identity-verifications/idv_test123/pdf-report",
            "pdf_data" => base64_encode("PDF report data content"),
            "generated_on" => "2023-03-15T12:00:00Z",
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/identity-verifications/idv_test123/pdf-report"
                ]
            ]
        ];
    }

    // Response validators
    private function validateIdentityVerificationAndAttemptResponse($response)
    {
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["attempt_id"]);
        $this->assertTrue(is_array($response["declared_data"]));
        $this->assertNotNull($response["redirect_url"]);
        $this->assertNotNull($response["status"]);
    }

    private function validateIdentityVerificationResponse($response)
    {
        $this->assertNotNull($response["id"]);
        if (isset($response["applicant_id"])) {
            $this->assertNotNull($response["applicant_id"]);
        }
        if (isset($response["declared_data"])) {
            $this->assertTrue(is_array($response["declared_data"]));
        }
        $this->assertNotNull($response["status"]);
    }

    private function validateIdentityVerificationAttemptResponse($response)
    {
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["status"]);
        if (isset($response["redirect_url"])) {
            $this->assertNotNull($response["redirect_url"]);
        }
    }

    private function validateIdentityVerificationAttemptsResponse($response)
    {
        $this->assertNotNull($response["count"]);
        $this->assertTrue(is_array($response["attempts"]));
        $this->assertGreaterThanOrEqual(0, $response["count"]);
    }

    private function validateIdentityVerificationPdfReportResponse($response)
    {
        $this->assertNotNull($response["id"]);
        if (isset($response["pdf_data"])) {
            $this->assertNotNull($response["pdf_data"]);
        }
        if (isset($response["report_url"])) {
            $this->assertNotNull($response["report_url"]);
        }
    }
}
