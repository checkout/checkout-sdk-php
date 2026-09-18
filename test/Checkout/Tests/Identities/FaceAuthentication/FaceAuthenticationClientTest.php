<?php

namespace Checkout\Tests\Identities\FaceAuthentication;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\Entities\AttemptsQueryFilter;
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
            ->method("query")
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
    public function shouldGetFaceAuthenticationAttemptAssets()
    {
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptAssetsResponse();

        $this->apiClient
            ->method("query")
            ->willReturn($expectedResponse);

        $query = new AttemptAssetsQueryFilter();
        $query->skip = 0;
        $query->limit = 10;
        $response = $this->client->getFaceAuthenticationAttemptAssets("face_auth_12345", "attempt_67890", $query);

        $this->assertNotNull($response);
        $this->validateFaceAuthenticationAttemptAssetsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetFaceAuthenticationAttemptAssets()
    {
        $faceAuthId = "face_auth_12345";
        $attemptId = "attempt_67890";
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptAssetsResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("query")
            ->with("face-authentications/" . $faceAuthId . "/attempts/" . $attemptId . "/assets")
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthenticationAttemptAssets($faceAuthId, $attemptId, new AttemptAssetsQueryFilter());

        $this->assertNotNull($response);
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
            ->method("query")
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

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetFaceAuthenticationAttemptsWithPagination()
    {
        $faceAuthenticationId = "face_auth_12345";
        $expectedResponse = $this->buildExpectedFaceAuthenticationAttemptsResponse();

        $query = new AttemptsQueryFilter();
        $query->skip = 5;
        $query->limit = 25;

        $this->apiClient
            ->expects($this->once())
            ->method("query")
            ->with("face-authentications/" . $faceAuthenticationId . "/attempts", $query)
            ->willReturn($expectedResponse);

        $response = $this->client->getFaceAuthenticationAttempts($faceAuthenticationId, $query);

        $this->assertNotNull($response);
        $this->assertSame("skip=5&limit=25", $query->getEncodedQueryParameters());
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
            "total_count" => 1,
            "skip" => 0,
            "limit" => 10,
            "data" => [
                [
                    "id" => "attempt_67890",
                    "status" => "pending",
                    "redirect_url" => "https://verify.checkout.com/attempt_67890",
                    "response_codes" => [],
                    "phone_number" => [
                        "country_code" => "+33",
                        "number" => "1234567890"
                    ],
                    "created_on" => "2024-03-20T10:30:00Z"
                ]
            ],
            "_links" => [
                "self" => [
                    "href" => "https://api.checkout.com/face-authentications/face_auth_12345/attempts"
                ]
            ]
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
        $this->assertArrayHasKey("total_count", $response);
        $this->assertArrayHasKey("skip", $response);
        $this->assertArrayHasKey("limit", $response);
        $this->assertArrayHasKey("data", $response);
        $this->assertArrayHasKey("_links", $response);

        $this->assertTrue(is_numeric($response["total_count"]));
        $this->assertTrue(is_array($response["data"]));
        $this->assertSame("+33", $response["data"][0]["phone_number"]["country_code"]);
    }

    private function buildExpectedFaceAuthenticationAttemptAssetsResponse(): array
    {
        return [
            "total_count" => 1,
            "skip" => 0,
            "limit" => 10,
            "data" => [
                [
                    "type" => "face_image",
                    "_links" => [
                        "asset_url" => [
                            "href" => "https://example.com/face-image.jpg"
                        ]
                    ]
                ]
            ]
        ];
    }

    private function validateFaceAuthenticationAttemptAssetsResponse(array $response): void
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
