<?php

namespace Checkout\Tests\Identities\AddressDocumentVerification;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\AddressDocumentVerification\AddressDocumentVerificationClient;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationRequest;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationAttemptRequest;
use Checkout\Identities\Entities\DeclaredData;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class AddressDocumentVerificationClientTest extends UnitTestFixture
{
    /**
     * @var AddressDocumentVerificationClient
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
        $this->client = new AddressDocumentVerificationClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAddressDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildAddressDocumentVerificationRequest();
        $response = $this->client->createAddressDocumentVerification($request);

        $this->assertNotNull($response);
        $this->validateAddressDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerification("adv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
        $this->validateAddressDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeAddressDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeAddressDocumentVerification("adv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
        $this->validateAddressDocumentVerificationResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateAddressDocumentVerificationAttempt()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationAttemptResponse();

        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildAddressDocumentVerificationAttemptRequest();
        $response = $this->client->createAddressDocumentVerificationAttempt("adv_tkoi5db4hryu5cei5vwoabr7we", $request);

        $this->assertNotNull($response);
        $this->validateAddressDocumentVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerificationAttempts()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationAttemptsResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerificationAttempts("adv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
        $this->validateAddressDocumentVerificationAttemptsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerificationAttempt()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationAttemptResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerificationAttempt(
            "adv_tkoi5db4hryu5cei5vwoabr7we",
            "adva_tkoi5db4hryu5cei5vwoabr7we"
        );

        $this->assertNotNull($response);
        $this->validateAddressDocumentVerificationAttemptResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetAddressDocumentVerificationReport()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerificationReport("adv_tkoi5db4hryu5cei5vwoabr7we");

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateAddressDocumentVerification()
    {
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("address-document-verifications")
            ->willReturn($expectedResponse);

        $request = $this->buildAddressDocumentVerificationRequest();
        $response = $this->client->createAddressDocumentVerification($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetAddressDocumentVerification()
    {
        $advId = "adv_tkoi5db4hryu5cei5vwoabr7we";
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("address-document-verifications/" . $advId)
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerification($advId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForAnonymizeAddressDocumentVerification()
    {
        $advId = "adv_tkoi5db4hryu5cei5vwoabr7we";
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("address-document-verifications/" . $advId . "/anonymize")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeAddressDocumentVerification($advId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateAddressDocumentVerificationAttempt()
    {
        $advId = "adv_tkoi5db4hryu5cei5vwoabr7we";
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationAttemptResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("address-document-verifications/" . $advId . "/attempts")
            ->willReturn($expectedResponse);

        $request = $this->buildAddressDocumentVerificationAttemptRequest();
        $response = $this->client->createAddressDocumentVerificationAttempt($advId, $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetAddressDocumentVerificationAttempts()
    {
        $advId = "adv_tkoi5db4hryu5cei5vwoabr7we";
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationAttemptsResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("address-document-verifications/" . $advId . "/attempts")
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerificationAttempts($advId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetAddressDocumentVerificationAttempt()
    {
        $advId = "adv_tkoi5db4hryu5cei5vwoabr7we";
        $attemptId = "adva_tkoi5db4hryu5cei5vwoabr7we";
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationAttemptResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("address-document-verifications/" . $advId . "/attempts/" . $attemptId)
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerificationAttempt($advId, $attemptId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetAddressDocumentVerificationReport()
    {
        $advId = "adv_tkoi5db4hryu5cei5vwoabr7we";
        $expectedResponse = $this->buildExpectedAddressDocumentVerificationResponse();

        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("address-document-verifications/" . $advId . "/pdf-report")
            ->willReturn($expectedResponse);

        $response = $this->client->getAddressDocumentVerificationReport($advId);

        $this->assertNotNull($response);
    }

    private function buildAddressDocumentVerificationRequest(): AddressDocumentVerificationRequest
    {
        $declaredData = new DeclaredData();
        $declaredData->name = "Hannah Bret";

        $request = new AddressDocumentVerificationRequest();
        $request->applicant_id = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $request->user_journey_id = "journey_123";
        $request->declared_data = $declaredData;

        return $request;
    }

    private function buildAddressDocumentVerificationAttemptRequest(): AddressDocumentVerificationAttemptRequest
    {
        $request = new AddressDocumentVerificationAttemptRequest();
        $request->document = "base64-encoded-document-image-data";

        return $request;
    }

    private function buildExpectedAddressDocumentVerificationResponse(): array
    {
        return [
            "id" => "adv_tkoi5db4hryu5cei5vwoabr7we",
            "applicant_id" => "aplt_7hr7swleu6guzjqesyxmyodnya",
            "user_journey_id" => "journey_123",
            "status" => "created",
            "address_document" => [
                "document_type" => "utility_bill",
                "issuer" => "EDF Energy",
                "full_names" => ["Hannah Bret"],
                "issue_date" => "2024-01-15",
                "address" => [
                    "address_line1" => "123 Main Street",
                    "city" => "London",
                    "zip" => "SW1A 1AA",
                    "country" => "GB"
                ]
            ],
            "response_codes" => [],
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/address-document-verifications/adv_id"]
            ],
            "created_on" => "2024-03-20T10:30:00Z",
            "modified_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedAddressDocumentVerificationAttemptResponse(): array
    {
        return [
            "id" => "adva_tkoi5db4hryu5cei5vwoabr7we",
            "status" => "checks_in_progress",
            "response_codes" => [],
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/address-document-verifications/adv_id/attempts/adva_id"]
            ],
            "created_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function buildExpectedAddressDocumentVerificationAttemptsResponse(): array
    {
        return [
            "total_count" => 1,
            "skip" => 0,
            "limit" => 10,
            "data" => [
                [
                    "id" => "adva_tkoi5db4hryu5cei5vwoabr7we",
                    "status" => "completed",
                    "response_codes" => [],
                    "_links" => [
                        "self" => ["href" => "https://api.checkout.com/adv/adv_id/attempts/adva_id"]
                    ],
                    "created_on" => "2024-03-20T10:30:00Z"
                ]
            ],
            "_links" => [
                "self" => ["href" => "https://api.checkout.com/address-document-verifications/adv_id/attempts"]
            ]
        ];
    }

    private function validateAddressDocumentVerificationResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("applicant_id", $response);
        $this->assertArrayHasKey("user_journey_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("response_codes", $response);
        $this->assertArrayHasKey("_links", $response);

        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["applicant_id"]);
        $this->assertNotNull($response["user_journey_id"]);
        $this->assertNotNull($response["status"]);
    }

    private function validateAddressDocumentVerificationAttemptResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertArrayHasKey("response_codes", $response);
        $this->assertArrayHasKey("_links", $response);

        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["status"]);
    }

    private function validateAddressDocumentVerificationAttemptsResponse(array $response): void
    {
        $this->assertArrayHasKey("total_count", $response);
        $this->assertArrayHasKey("skip", $response);
        $this->assertArrayHasKey("limit", $response);
        $this->assertArrayHasKey("data", $response);
        $this->assertArrayHasKey("_links", $response);

        $this->assertNotNull($response["data"]);
        $this->assertTrue(is_array($response["data"]));
        $this->assertNotNull($response["total_count"]);
        $this->assertTrue(is_numeric($response["total_count"]));
    }
}
