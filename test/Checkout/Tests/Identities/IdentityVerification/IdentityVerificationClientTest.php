<?php

namespace Checkout\Tests\Identities\IdentityVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\Entities\AttemptsQueryFilter;
use Checkout\Identities\Entities\IdentityDeclaredData;
use Checkout\Identities\Entities\IdentityVerificationClientInformation;
use Checkout\Identities\Entities\PhoneNumber;
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
            ->method("query")
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
            ->method("query")
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

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttemptAssets()
    {
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptAssetsResponse();

        $this->apiClient
            ->method("query")
            ->willReturn($expectedResponse);

        $query = new AttemptAssetsQueryFilter();
        $query->skip = 0;
        $query->limit = 10;
        $response = $this->client->getIdentityVerificationAttemptAssets("idv_test123", "att_test456", $query);

        $this->assertNotNull($response);
        $this->validateIdentityVerificationAttemptAssetsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdentityVerificationAttemptAssets()
    {
        $identityVerificationId = "idv_test123";
        $attemptId = "att_test456";
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptAssetsResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("query")
            ->with("identity-verifications/" . $identityVerificationId . "/attempts/" . $attemptId . "/assets")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationAttemptAssets($identityVerificationId, $attemptId, new AttemptAssetsQueryFilter());

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdentityVerificationAttemptsWithPagination()
    {
        $identityVerificationId = "idv_test123";
        $expectedResponse = $this->buildExpectedIdentityVerificationAttemptsResponse();

        $query = new AttemptsQueryFilter();
        $query->skip = 5;
        $query->limit = 25;

        $this->apiClient
            ->expects($this->once())
            ->method("query")
            ->with("identity-verifications/" . $identityVerificationId . "/attempts", $query)
            ->willReturn($expectedResponse);

        $response = $this->client->getIdentityVerificationAttempts($identityVerificationId, $query);

        $this->assertNotNull($response);
        $this->assertSame("skip=5&limit=25", $query->getEncodedQueryParameters());
    }

    // Request builders
    private function buildIdentityVerificationAndOpenRequest()
    {
        $declared_data = new IdentityDeclaredData();
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
        $declared_data = new IdentityDeclaredData();
        $declared_data->name = "Jane Smith";

        $request = new IdentityVerificationRequest();
        $request->applicant_id = "applicant_test456";
        $request->declared_data = $declared_data;
        $request->user_journey_id = "uj_test789";

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
        $phone_number->country_code = "+33";
        $phone_number->number = "5555550102";

        $request = new IdentityVerificationAttemptRequest();
        $request->redirect_url = "https://example.com/success";
        $request->phone_number = $phone_number;
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
            "total_count" => 2,
            "skip" => 0,
            "limit" => 10,
            "data" => [
                [
                    "id" => "att_test456",
                    "status" => "pending",
                    "redirect_url" => "https://verify.checkout.com/att_test456",
                    "response_codes" => [],
                    "phone_number" => [
                        "country_code" => "+33",
                        "number" => "1234567890"
                    ],
                    "client_information" => [
                        "pre_selected_residence_country" => "GB",
                        "pre_selected_document_issuing_country" => "GB",
                        "pre_selected_document_type" => "Passport"
                    ],
                    "created_on" => "2023-03-15T10:35:00Z"
                ],
                [
                    "id" => "att_test789",
                    "status" => "completed",
                    "redirect_url" => "https://verify.checkout.com/att_test789",
                    "response_codes" => [],
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
        $this->assertNotNull($response["total_count"]);
        $this->assertArrayHasKey("skip", $response);
        $this->assertArrayHasKey("limit", $response);
        $this->assertTrue(is_array($response["data"]));
        $this->assertGreaterThanOrEqual(0, $response["total_count"]);
        $this->assertSame("+33", $response["data"][0]["phone_number"]["country_code"]);
        $this->assertSame("GB", $response["data"][0]["client_information"]["pre_selected_document_issuing_country"]);
        $this->assertSame("Passport", $response["data"][0]["client_information"]["pre_selected_document_type"]);
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

    private function buildExpectedIdentityVerificationAttemptAssetsResponse(): array
    {
        return [
            "total_count" => 1,
            "skip" => 0,
            "limit" => 10,
            "data" => [
                [
                    "type" => "document_front_image",
                    "_links" => [
                        "asset_url" => [
                            "href" => "https://example.com/document-front.jpg"
                        ]
                    ]
                ]
            ]
        ];
    }

    private function validateIdentityVerificationAttemptAssetsResponse($response)
    {
        $this->assertArrayHasKey("total_count", $response);
        $this->assertArrayHasKey("skip", $response);
        $this->assertArrayHasKey("limit", $response);
        $this->assertArrayHasKey("data", $response);

        $this->assertTrue(is_array($response["data"]));
        if (count($response["data"]) > 0) {
            $this->assertNotNull($response["data"][0]["type"]);
            $this->assertNotNull($response["data"][0]["_links"]["asset_url"]["href"]);
        }
    }
}
