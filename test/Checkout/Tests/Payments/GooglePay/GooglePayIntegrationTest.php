<?php

namespace Checkout\Tests\Payments\GooglePay;

use Checkout\CheckoutApiException;
use Checkout\Payments\GooglePay\Requests\GooglePayEnrollmentRequest;
use Checkout\Payments\GooglePay\Requests\GooglePayRegisterDomainRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class GooglePayIntegrationTest extends SandboxTestFixture
{
    /**
     * @before
     */
    public function before()
    {
        $this->init(PlatformType::$default_oauth);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateEnrollment()
    {
        $this->markTestSkipped("requires a valid entity with Google Pay enrollment permissions");

        $request = $this->buildValidEnrollmentRequest();

        $response = $this->checkoutApi->getGooglePayClient()->createEnrollment($request);

        $this->validateCreateEnrollmentResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRegisterDomain()
    {
        $this->markTestSkipped("requires an enrolled entity and domain ownership verification");

        $entityId = "ent_uzm3uxtssvmuxnyrfdffcyjxeu"; // Replace with enrolled entity ID
        $request = $this->buildValidRegisterDomainRequest();

        $response = $this->checkoutApi->getGooglePayClient()->registerDomain($entityId, $request);

        $this->validateRegisterDomainResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetRegisteredDomains()
    {
        $this->markTestSkipped("requires an enrolled entity with registered domains");

        $entityId = "ent_uzm3uxtssvmuxnyrfdffcyjxeu"; // Replace with enrolled entity ID

        $response = $this->checkoutApi->getGooglePayClient()->getRegisteredDomains($entityId);

        $this->validateGetRegisteredDomainsResponse($response, $entityId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGetEnrollmentState()
    {
        $this->markTestSkipped("requires a valid entity to check enrollment state");

        $entityId = "ent_uzm3uxtssvmuxnyrfdffcyjxeu"; // Replace with valid entity ID

        $response = $this->checkoutApi->getGooglePayClient()->getEnrollmentState($entityId);

        $this->validateGetEnrollmentStateResponse($response, $entityId);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompleteGooglePayEnrollmentWorkflow()
    {
        $this->markTestSkipped("requires entity setup and domain ownership verification");

        $entityId = "ent_uzm3uxtssvmuxnyrfdffcyjxeu";
        
        // Step 1: Create enrollment
        $enrollmentRequest = $this->buildValidEnrollmentRequest();
        $enrollmentResponse = $this->checkoutApi->getGooglePayClient()->createEnrollment($enrollmentRequest);
        $this->validateCreateEnrollmentResponse($enrollmentResponse, $enrollmentRequest);

        // Step 2: Register domain
        $domainRequest = $this->buildValidRegisterDomainRequest();
        $domainResponse = $this->checkoutApi->getGooglePayClient()->registerDomain($entityId, $domainRequest);
        $this->validateRegisterDomainResponse($domainResponse, $domainRequest);

        // Step 3: Verify enrollment state
        $stateResponse = $this->checkoutApi->getGooglePayClient()->getEnrollmentState($entityId);
        $this->validateGetEnrollmentStateResponse($stateResponse, $entityId);

        // Step 4: Get registered domains list
        $domainsResponse = $this->checkoutApi->getGooglePayClient()->getRegisteredDomains($entityId);
        $this->validateGetRegisteredDomainsResponse($domainsResponse, $entityId);

        // Verify the domain we registered is in the list
        $registeredDomains = array_column($domainsResponse["domains"], "web_domain");
        $this->assertTrue(in_array($domainRequest->webDomain, $registeredDomains));
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRegisterMultipleDomains()
    {
        $this->markTestSkipped("requires an enrolled entity and multiple domain ownership verification");

        $entityId = "ent_uzm3uxtssvmuxnyrfdffcyjxeu";
        $domains = ["example.com", "shop.example.com", "api.example.com"];

        foreach ($domains as $domain) {
            $request = new GooglePayRegisterDomainRequest();
            $request->webDomain = $domain;
            
            $response = $this->checkoutApi->getGooglePayClient()->registerDomain($entityId, $request);
            $this->validateRegisterDomainResponse($response, $request);
        }

        // Verify all domains are registered
        $domainsResponse = $this->checkoutApi->getGooglePayClient()->getRegisteredDomains($entityId);
        $this->validateGetRegisteredDomainsResponse($domainsResponse, $entityId);
        
        $registeredDomains = array_column($domainsResponse["domains"], "web_domain");
        foreach ($domains as $domain) {
            $this->assertTrue(in_array($domain, $registeredDomains), "Domain {$domain} should be registered");
        }
    }

    // Common builder methods for DRY setup
    private function buildValidEnrollmentRequest(): GooglePayEnrollmentRequest
    {
        $request = new GooglePayEnrollmentRequest();
        $request->entityId = "ent_uzm3uxtssvmuxnyrfdffcyjxeu"; // Use valid entity ID
        $request->emailAddress = "test@example.com";
        $request->acceptTermsOfService = true;
        return $request;
    }

    private function buildValidRegisterDomainRequest(): GooglePayRegisterDomainRequest
    {
        $request = new GooglePayRegisterDomainRequest();
        $request->webDomain = "example.com"; // Must be owned and verified domain
        return $request;
    }

    private function buildRegisterDomainRequestForSubdomain(string $subdomain): GooglePayRegisterDomainRequest
    {
        $request = new GooglePayRegisterDomainRequest();
        $request->webDomain = $subdomain . ".example.com";
        return $request;
    }

    private function validateCreateEnrollmentResponse(array $response, GooglePayEnrollmentRequest $originalRequest): void
    {
        $this->assertResponse($response, "id", "entity_id", "status");
        
        $this->assertEquals($originalRequest->entityId, $response["entity_id"]);
        $this->assertNotEmpty($response["id"]);
        $this->assertTrue(in_array($response["status"], ["enrolled", "pending"]));
        
        if (isset($response["email_address"])) {
            $this->assertEquals($originalRequest->emailAddress, $response["email_address"]);
        }
    }

    private function validateRegisterDomainResponse(array $response, GooglePayRegisterDomainRequest $originalRequest): void
    {
        $this->assertResponse($response, "web_domain", "status");
        
        $this->assertEquals($originalRequest->webDomain, $response["web_domain"]);
        $this->assertTrue(in_array($response["status"], ["registered", "pending", "verified"]));
        
        if (isset($response["registered_on"])) {
            $this->assertNotEmpty($response["registered_on"]);
        }
    }

    private function validateGetRegisteredDomainsResponse(array $response, string $expectedEntityId): void
    {
        $this->assertResponse($response, "domains");
        
        $this->assertTrue(is_array($response["domains"]));
        
        foreach ($response["domains"] as $domain) {
            $this->assertArrayHasKey("web_domain", $domain);
            $this->assertArrayHasKey("status", $domain);
            $this->assertNotEmpty($domain["web_domain"]);
            $this->assertTrue(in_array($domain["status"], ["registered", "pending", "verified"]));
        }
    }

    private function validateGetEnrollmentStateResponse(array $response, string $expectedEntityId): void
    {
        $this->assertResponse($response, "entity_id", "status");
        
        $this->assertEquals($expectedEntityId, $response["entity_id"]);
        $this->assertTrue(in_array($response["status"], ["enrolled", "pending", "rejected"]));
        
        if (isset($response["enrollment_date"])) {
            $this->assertNotEmpty($response["enrollment_date"]);
        }
        
        if (isset($response["capabilities"])) {
            $this->assertTrue(is_array($response["capabilities"]));
        }
        
        if (isset($response["verification_status"])) {
            $this->assertTrue(in_array($response["verification_status"], ["verified", "pending", "failed"]));
        }
    }
}
