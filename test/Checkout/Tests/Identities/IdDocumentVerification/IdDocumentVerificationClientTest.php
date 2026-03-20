<?php

namespace Checkout\Tests\Identities\IdDocumentVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\Identities\IdDocumentVerification\IdDocumentVerificationClient;
use Checkout\Identities\IdDocumentVerification\Requests\IdDocumentVerificationRequest;
use Checkout\Identities\IdDocumentVerification\Requests\IdDocumentVerificationAttemptRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class IdDocumentVerificationClientTest extends UnitTestFixture
{
    /**
     * @var IdDocumentVerificationClient
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
        $this->client = new IdDocumentVerificationClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildIdDocumentVerificationRequest();
        $response = $this->client->createIdDocumentVerification($request);

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerification("iddoc_12345");

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeIdDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeIdDocumentVerification("iddoc_12345");

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateIdDocumentVerificationAttempt()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationAttemptResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildIdDocumentVerificationAttemptRequest();
        $response = $this->client->createIdDocumentVerificationAttempt("iddoc_12345", $request);

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerificationAttempts()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationAttemptsResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerificationAttempts("iddoc_12345");

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationAttemptsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerificationAttempt()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationAttemptResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerificationAttempt("iddoc_12345", "attempt_67890");

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetIdDocumentVerificationReport()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationReportResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerificationReport("iddoc_12345");

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationReportResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateIdDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("id-document-verifications")
            ->willReturn($expectedResponse);

        $request = $this->buildIdDocumentVerificationRequest();
        $response = $this->client->createIdDocumentVerification($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdDocumentVerification()
    {
        $idDocVerificationId = "iddoc_12345";
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("id-document-verifications/" . $idDocVerificationId)
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerification($idDocVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForAnonymizeIdDocumentVerification()
    {
        $idDocVerificationId = "iddoc_12345";
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("id-document-verifications/" . $idDocVerificationId . "/anonymize")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeIdDocumentVerification($idDocVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateIdDocumentVerificationAttempt()
    {
        $idDocVerificationId = "iddoc_12345";
        $expectedResponse = $this->buildExpectedIdDocumentVerificationAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("id-document-verifications/" . $idDocVerificationId . "/attempts")
            ->willReturn($expectedResponse);

        $request = $this->buildIdDocumentVerificationAttemptRequest();
        $response = $this->client->createIdDocumentVerificationAttempt($idDocVerificationId, $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdDocumentVerificationAttempts()
    {
        $idDocVerificationId = "iddoc_12345";
        $expectedResponse = $this->buildExpectedIdDocumentVerificationAttemptsResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("id-document-verifications/" . $idDocVerificationId . "/attempts")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerificationAttempts($idDocVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdDocumentVerificationAttempt()
    {
        $idDocVerificationId = "iddoc_12345";
        $attemptId = "attempt_67890";
        $expectedResponse = $this->buildExpectedIdDocumentVerificationAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("id-document-verifications/" . $idDocVerificationId . "/attempts/" . $attemptId)
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerificationAttempt($idDocVerificationId, $attemptId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetIdDocumentVerificationReport()
    {
        $idDocVerificationId = "iddoc_12345";
        $expectedResponse = $this->buildExpectedIdDocumentVerificationReportResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("id-document-verifications/" . $idDocVerificationId . "/pdf-report")
            ->willReturn($expectedResponse);

        $response = $this->client->getIdDocumentVerificationReport($idDocVerificationId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleCreateIdDocumentVerificationWithAllParameters()
    {
        $expectedResponse = $this->buildExpectedIdDocumentVerificationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildIdDocumentVerificationRequest();
        
        $response = $this->client->createIdDocumentVerification($request);

        $this->assertNotNull($response);
        $this->validateIdDocumentVerificationResponse($response);
    }

    private function buildIdDocumentVerificationRequest(): IdDocumentVerificationRequest
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "John Doe";

        $request = new IdDocumentVerificationRequest();
        $request->applicant_id = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $request->user_journey_id = "journey_123";
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

    private function buildExpectedIdDocumentVerificationResponse(): array
    {
        return [
            "id" => "iddoc_12345",
            "applicant_id" => "aplt_7hr7swleu6guzjqesyxmyodnya",
            "user_journey_id" => "journey_123",
            "status" => "created",
            "declared_data" => [
                "name" => "John Doe"
            ],
            "created_on" => "2024-03-20T10:30:00Z",
            "modified_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedIdDocumentVerificationAttemptResponse(): array
    {
        return [
            "id" => "attempt_67890",
            "id_document_verification_id" => "iddoc_12345",
            "status" => "pending",
            "created_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedIdDocumentVerificationAttemptsResponse(): array
    {
        return [
            "data" => [
                [
                    "id" => "attempt_67890",
                    "id_document_verification_id" => "iddoc_12345",
                    "status" => "pending",
                    "created_on" => "2024-03-20T10:30:00Z"
                ]
            ],
            "total_count" => 1
        ];
    }

    private function buildExpectedIdDocumentVerificationReportResponse(): array
    {
        return [
            "id" => "iddoc_12345",
            "pdf_url" => "https://example.com/report.pdf",
            "generated_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function validateIdDocumentVerificationResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("applicant_id", $response);
        $this->assertArrayHasKey("user_journey_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("created_on", $response);
        
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["applicant_id"]);
        $this->assertNotNull($response["user_journey_id"]);
        $this->assertNotNull($response["status"]);
        $this->assertNotNull($response["created_on"]);
    }

    private function validateIdDocumentVerificationAttemptResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("id_document_verification_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("created_on", $response);
        
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["id_document_verification_id"]);
        $this->assertNotNull($response["status"]);
        $this->assertNotNull($response["created_on"]);
    }

    private function validateIdDocumentVerificationAttemptsResponse(array $response): void
    {
        $this->assertArrayHasKey("data", $response);
        $this->assertArrayHasKey("total_count", $response);
        
        $this->assertNotNull($response["data"]);
        $this->assertTrue(is_array($response["data"]));
        $this->assertNotNull($response["total_count"]);
        $this->assertTrue(is_numeric($response["total_count"]));
    }

    private function validateIdDocumentVerificationReportResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("pdf_url", $response);
        
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["pdf_url"]);
    }
}
