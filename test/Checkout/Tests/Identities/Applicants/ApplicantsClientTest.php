<?php

namespace Checkout\Tests\Identities\Applicants;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Identities\Applicants\ApplicantsClient;
use Checkout\Identities\Applicants\Requests\ApplicantRequest;
use Checkout\Identities\Applicants\Requests\ApplicantUpdateRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ApplicantsClientTest extends UnitTestFixture
{
    /**
     * @var ApplicantsClient
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
        $this->client = new ApplicantsClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateApplicant()
    {
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildApplicantRequest();
        $response = $this->client->createApplicant($request);

        $this->assertNotNull($response);
        $this->validateApplicantResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetApplicant()
    {
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->method("get")
            ->willReturn($expectedResponse);

        $response = $this->client->getApplicant("aplt_7hr7swleu6guzjqesyxmyodnya");

        $this->assertNotNull($response);
        $this->validateApplicantResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateApplicant()
    {
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->method("patch")
            ->willReturn($expectedResponse);

        $request = $this->buildApplicantUpdateRequest();
        $response = $this->client->updateApplicant("aplt_7hr7swleu6guzjqesyxmyodnya", $request);

        $this->assertNotNull($response);
        $this->validateApplicantResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldAnonymizeApplicant()
    {
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeApplicant("aplt_7hr7swleu6guzjqesyxmyodnya");

        $this->assertNotNull($response);
        $this->validateApplicantResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForCreateApplicant()
    {
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("applicants")
            ->willReturn($expectedResponse);

        $request = $this->buildApplicantRequest();
        $response = $this->client->createApplicant($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForGetApplicant()
    {
        $applicantId = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("get")
            ->with("applicants/" . $applicantId)
            ->willReturn($expectedResponse);

        $response = $this->client->getApplicant($applicantId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForUpdateApplicant()
    {
        $applicantId = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("patch")
            ->with("applicants/" . $applicantId)
            ->willReturn($expectedResponse);

        $request = $this->buildApplicantUpdateRequest();
        $response = $this->client->updateApplicant($applicantId, $request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCallCorrectApiEndpointForAnonymizeApplicant()
    {
        $applicantId = "aplt_7hr7swleu6guzjqesyxmyodnya";
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->expects($this->once())
            ->method("post")
            ->with("applicants/" . $applicantId . "/anonymize")
            ->willReturn($expectedResponse);

        $response = $this->client->anonymizeApplicant($applicantId);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldHandleCreateApplicantWithAllParameters()
    {
        $expectedResponse = $this->buildExpectedApplicantResponse();
        
        $this->apiClient
            ->method("post")
            ->willReturn($expectedResponse);

        $request = $this->buildApplicantRequest();
        $request->external_applicant_id = "ext_test_123";
        
        $response = $this->client->createApplicant($request);

        $this->assertNotNull($response);
        $this->validateApplicantResponse($response);
    }

    private function buildApplicantRequest(): ApplicantRequest
    {
        $request = new ApplicantRequest();
        $request->external_applicant_id = "ext_applicant_123";
        $request->email = "test.applicant@example.com";
        $request->external_applicant_name = "John Doe";

        return $request;
    }

    private function buildApplicantUpdateRequest(): ApplicantUpdateRequest
    {
        $request = new ApplicantUpdateRequest();
        $request->email = "updated.applicant@example.com";
        $request->external_applicant_name = "John Updated Doe";

        return $request;
    }

    private function buildExpectedApplicantResponse(): array
    {
        return [
            "id" => "aplt_7hr7swleu6guzjqesyxmyodnya",
            "external_applicant_id" => "ext_applicant_123",
            "email" => "test.applicant@example.com",
            "external_applicant_name" => "John Doe",
            "created_on" => "2024-03-20T10:30:00Z",
            "modified_on" => "2024-03-20T10:30:00Z"
        ];
    }

    private function validateApplicantResponse(array $response): void
    {
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("email", $response);
        $this->assertArrayHasKey("external_applicant_name", $response);
        $this->assertArrayHasKey("created_on", $response);
        $this->assertArrayHasKey("modified_on", $response);
        
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["email"]);
        $this->assertNotNull($response["external_applicant_name"]);
        $this->assertNotNull($response["created_on"]);
        $this->assertNotNull($response["modified_on"]);
    }
}
