<?php

namespace Checkout\Tests\Payments\GooglePay;

use Checkout\CheckoutApiException;
use Checkout\Payments\GooglePay\GooglePayClient;
use Checkout\Payments\GooglePay\Requests\GooglePayEnrollmentRequest;
use Checkout\Payments\GooglePay\Requests\GooglePayRegisterDomainRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class GooglePayClientTest extends UnitTestFixture
{
    /**
     * @var GooglePayClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default_oauth);
        $this->client = new GooglePayClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateEnrollment()
    {
        $request = $this->buildValidEnrollmentRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("googlepay/enrollments"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildCreateEnrollmentResponse());

        $response = $this->client->createEnrollment($request);

        $this->validateCreateEnrollmentResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRegisterDomain()
    {
        $entityId = "ent_test123456789";
        $request = $this->buildValidRegisterDomainRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("googlepay/enrollments/{$entityId}/domain"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildRegisterDomainResponse());

        $response = $this->client->registerDomain($entityId, $request);

        $this->validateRegisterDomainResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetRegisteredDomains()
    {
        $entityId = "ent_test123456789";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("googlepay/enrollments/{$entityId}/domains"),
                $this->anything()
            )
            ->willReturn($this->buildGetRegisteredDomainsResponse());

        $response = $this->client->getRegisteredDomains($entityId);

        $this->validateGetRegisteredDomainsResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEnrollmentState()
    {
        $entityId = "ent_test123456789";

        $this->apiClient
            ->method("get")
            ->with(
                $this->equalTo("googlepay/enrollments/{$entityId}/state"),
                $this->anything()
            )
            ->willReturn($this->buildGetEnrollmentStateResponse());

        $response = $this->client->getEnrollmentState($entityId);

        $this->validateGetEnrollmentStateResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateEnrollmentWithMinimalData()
    {
        $request = $this->buildMinimalEnrollmentRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("googlepay/enrollments"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildCreateEnrollmentResponse());

        $response = $this->client->createEnrollment($request);

        $this->validateCreateEnrollmentResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRegisterDomainWithSubdomain()
    {
        $entityId = "ent_test123456789";
        $request = $this->buildRegisterDomainRequestWithSubdomain();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("googlepay/enrollments/{$entityId}/domain"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn($this->buildRegisterDomainResponse());

        $response = $this->client->registerDomain($entityId, $request);

        $this->validateRegisterDomainResponse($response);
    }

    // Common builder methods for DRY setup
    private function buildValidEnrollmentRequest(): GooglePayEnrollmentRequest
    {
        $request = new GooglePayEnrollmentRequest();
        $request->entity_id = "ent_test123456789";
        $request->email_address = "test@example.com";
        $request->accept_terms_of_service = true;
        return $request;
    }

    private function buildMinimalEnrollmentRequest(): GooglePayEnrollmentRequest
    {
        $request = new GooglePayEnrollmentRequest();
        $request->entity_id = "ent_minimal123";
        $request->email_address = "minimal@test.com";
        $request->accept_terms_of_service = true;
        return $request;
    }

    private function buildValidRegisterDomainRequest(): GooglePayRegisterDomainRequest
    {
        $request = new GooglePayRegisterDomainRequest();
        $request->web_domain = "example.com";
        return $request;
    }

    private function buildRegisterDomainRequestWithSubdomain(): GooglePayRegisterDomainRequest
    {
        $request = new GooglePayRegisterDomainRequest();
        $request->web_domain = "shop.example.com";
        return $request;
    }

    private function buildCreateEnrollmentResponse(): array
    {
        return [
            "id" => "enr_12345678901234567890",
            "entity_id" => "ent_test123456789",
            "status" => "enrolled",
            "created_on" => "2024-03-11T10:30:00Z",
            "email_address" => "test@example.com"
        ];
    }

    private function buildRegisterDomainResponse(): array
    {
        return [
            "web_domain" => "example.com",
            "status" => "registered",
            "registered_on" => "2024-03-11T10:32:00Z"
        ];
    }

    private function buildGetRegisteredDomainsResponse(): array
    {
        return [
            "domains" => [
                [
                    "web_domain" => "example.com",
                    "status" => "registered",
                    "registered_on" => "2024-03-11T10:30:00Z"
                ],
                [
                    "web_domain" => "shop.example.com",
                    "status" => "pending",
                    "registered_on" => "2024-03-11T10:32:00Z"
                ]
            ]
        ];
    }

    private function buildGetEnrollmentStateResponse(): array
    {
        return [
            "entity_id" => "ent_test123456789",
            "status" => "enrolled",
            "enrollment_date" => "2024-03-11T10:30:00Z",
            "capabilities" => ["web_payments"],
            "verification_status" => "verified"
        ];
    }

    private function validateCreateEnrollmentResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("id", $response);
        $this->assertArrayHasKey("entity_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertEquals("enr_12345678901234567890", $response["id"]);
        $this->assertEquals("ent_test123456789", $response["entity_id"]);
        $this->assertEquals("enrolled", $response["status"]);
    }

    private function validateRegisterDomainResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("web_domain", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertEquals("example.com", $response["web_domain"]);
        $this->assertTrue(in_array($response["status"], ["registered", "pending", "verified"]));
    }

    private function validateGetRegisteredDomainsResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("domains", $response);
        $this->assertTrue(is_array($response["domains"]));
        
        if (!empty($response["domains"])) {
            $firstDomain = $response["domains"][0];
            $this->assertArrayHasKey("web_domain", $firstDomain);
            $this->assertArrayHasKey("status", $firstDomain);
        }
    }

    private function validateGetEnrollmentStateResponse(array $response): void
    {
        $this->assertNotNull($response);
        $this->assertArrayHasKey("entity_id", $response);
        $this->assertArrayHasKey("status", $response);
        $this->assertEquals("ent_test123456789", $response["entity_id"]);
        $this->assertTrue(in_array($response["status"], ["enrolled", "pending", "rejected"]));
        
        if (isset($response["capabilities"])) {
            $this->assertTrue(is_array($response["capabilities"]));
        }
    }
}
