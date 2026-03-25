<?php

namespace Checkout\Tests\Identities\FaceAuthentication;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\ClientInformation;
use Checkout\Identities\FaceAuthentication\FaceAuthenticationClient;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationRequest;
use Checkout\Identities\FaceAuthentication\Requests\FaceAuthenticationAttemptRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class FaceAuthenticationClientTest extends UnitTestFixture
{
    /**
     * @var FaceAuthenticationClient
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
        $this->client = new FaceAuthenticationClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateFaceAuthentication()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildFaceAuthenticationRequest();
        $response = $this->client->createFaceAuthentication($request);

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthentication()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthentication("face_auth_12345");

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeFaceAuthentication()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeFaceAuthentication("face_auth_12345");

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateFaceAuthenticationAttempt()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildFaceAuthenticationAttemptRequest();
        $response = $this->client->createFaceAuthenticationAttempt("face_auth_12345", $request);

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthenticationAttempts()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptsResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthenticationAttempts("face_auth_12345");

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationAttemptsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthenticationAttempt()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthenticationAttempt("face_auth_12345", "attempt_67890");

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateFaceAuthentication()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("face-authentications")
            ->willReturn($expectedResponse);

        $request = $this->buildFaceAuthenticationRequest();
        $response = $this->client->createFaceAuthentication($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetFaceAuthentication()
    {
        $faceAuthId = "face_auth_12345";
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("face-authentications/" . $faceAuthId)
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthentication($faceAuthId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForAnonymizeFaceAuthentication()
    {
        $faceAuthId = "face_auth_12345";
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("face-authentications/" . $faceAuthId . "/anonymize")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeFaceAuthentication($faceAuthId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateFaceAuthenticationAttempt()
    {
        $faceAuthId = "face_auth_12345";
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("face-authentications/" . $faceAuthId . "/attempts")
            ->willReturn($expectedResponse);

        $request = $this->buildFaceAuthenticationAttemptRequest();
        $response = $this->client->createFaceAuthenticationAttempt($faceAuthId, $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetFaceAuthenticationAttempts()
    {
        $faceAuthId = "face_auth_12345";
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptsResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("face-authentications/" . $faceAuthId . "/attempts")
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthenticationAttempts($faceAuthId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetFaceAuthenticationAttempt()
    {
        $faceAuthId = "face_auth_12345";
        $attemptId = "attempt_67890";
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("face-authentications/" . $faceAuthId . "/attempts/" . $attemptId)
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthenticationAttempt($faceAuthId, $attemptId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleCreateFaceAuthenticationWithAllParameters()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildFaceAuthenticationRequest();
        
        $response = $this->client->createFaceAuthentication($request);

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationResponse($response);
    }

    private function buildFaceAuthenticationRequest(): FaceAuthenticationRequest
    {
        $request = new FaceAuthenticationRequest();
        $request->applicant_id = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $request->user_journey_id = "journey_123";

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

    private function buildExpectedFaceAuthenticationResponse(): array
    {
        return [
            "id" => "face_auth_12345",
            "applicant_id" => "aplt_7hr7swleu6guzjqesyxmyodnya",
            "user_journey_id" => "journey_123",
            "status" => "created",
            "created_on" => "2024-03-20T10:30:00Z",
            "modified_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedFaceAuthenticationAttemptResponse(): array
    {
        return [
            "id" => "attempt_67890",
            "face_authentication_id" => "face_auth_12345",
            "redirect_url" => "https://example.com/redirect",
            "status" => "pending",
            "created_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedFaceAuthenticationAttemptsResponse(): array
    {
        return [
            "attempts" => [
                [
                    "id" => "attempt_67890",
                    "face_authentication_id" => "face_auth_12345",
                    "status" => "pending",
                    "created_on" => "2024-03-20T10:30:00Z"
                ]
            ],
            "total_count" => 1
        ];
    }

    private function validateFaceAuthenticationResponse(array $response): void
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

    private function validateFaceAuthenticationAttemptResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("face_authentication_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("created_on", $response);
        
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["face_authentication_id"]);
        $this->assertNotNull($response["status"]);
        $this->assertNotNull($response["created_on"]);
    }

    private function validateFaceAuthenticationAttemptsResponse(array $response): void
    {
        $this->assertArrayHasKey("attempts", $response);
        $this->assertArrayHasKey("total_count", $response);
        
        $this->assertNotNull($response["attempts"]);
        $this->assertTrue(is_array($response["attempts"]));
        $this->assertNotNull($response["total_count"]);
        $this->assertTrue(is_numeric($response["total_count"]));
    }
}
