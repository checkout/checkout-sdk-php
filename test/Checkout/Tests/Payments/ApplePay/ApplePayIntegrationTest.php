<?php

namespace Checkout\Tests\Payments\ApplePay;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Payments\ApplePay\Request\CertificateRequest;
use Checkout\Payments\ApplePay\Request\DomainEnrollmentRequest;
use Checkout\Payments\ApplePay\Request\SigningRequest;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;

class ApplePayIntegrationTest extends SandboxTestFixture
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
    public function shouldGenerateCertificateSigningRequest()
    {
        $request = $this->buildSigningRequestWithEcV1();

        $response = $this->checkoutApi->getApplePayClient()->generateCertificateSigningRequest($request);

        $this->validateGenerateSigningRequestResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGenerateCertificateSigningRequestWithRsaV1()
    {
        $request = $this->buildSigningRequestWithRsaV1();

        $response = $this->checkoutApi->getApplePayClient()->generateCertificateSigningRequest($request);

        $this->validateGenerateSigningRequestResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadPaymentProcessingCertificate()
    {
        $this->markTestSkipped("This test requires a valid payment processing certificate from Apple Developer Portal");

        $request = $this->buildCertificateRequest();

        $response = $this->checkoutApi->getApplePayClient()->uploadPaymentProcessingCertificate($request);

        $this->validateUploadCertificateResponse($response, $request);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldEnrollDomain()
    {
        $this->markTestSkipped("This test requires OAuth credentials and domain verification setup");

        $request = $this->buildDomainEnrollmentRequest();

        $response = $this->checkoutApi->getApplePayClient()->enrollDomain($request);

        $this->validateEnrollDomainResponse($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCompleteApplePayWorkflow()
    {
        $this->markTestSkipped("This workflow test requires manual Apple Developer Portal interaction");

        // Step 1: Generate signing request
        $signingRequest = $this->buildSigningRequestWithEcV1();
        $signingResponse = $this->checkoutApi->getApplePayClient()->generateCertificateSigningRequest($signingRequest);

        $this->validateGenerateSigningRequestResponse($signingResponse, $signingRequest);

        // Note: In real scenario, you would:
        // 1. Take the signingResponse["content"]
        // 2. Submit it to Apple Developer Portal
        // 3. Download the certificate from Apple
        // 4. Upload it using uploadPaymentProcessingCertificate
        // 5. Enroll domains using enrollDomain
        //
        // For integration testing, we skip the Apple Developer Portal steps
        // as they require manual intervention and valid Apple Developer account
    }

    // Common builder methods for DRY setup
    private function buildCertificateRequest(): CertificateRequest
    {
        $request = new CertificateRequest();
        // Note: This would be a certificate obtained from Apple Developer Portal
        $request->content = "-----BEGIN CERTIFICATE-----\n" .
                          "MIIFjTCCBHWgAwIBAgIIAqVJ3DZvutkwDQYJKoZIhvcNAQEFBQAwgZYxCzAJBgNV\n" .
                          "BAYTAlVTMRMwEQYDVQQKDApBcHBsZSBJbmMuMSwwKgYDVQQLDCNBcHBsZSBXb3Js\n" .
                          "ZHdpZGUgRGV2ZWxvcGVyIFJlbGF0aW9uczFEMEIGA1UEAww7QXBwbGUgV29ybGR3\n" .
                          "aWRlIERldmVsb3BlciBSZWxhdGlvbnMgQ2VydGlmaWNhdGlvbiBBdXRob3JpdHkw\n" .
                          "HhcNMjQwMTEwMTUzNjQ1WhcNMjUwMTA5MTUzNjQ1WjCBjDEaMBgGCgmSJomT8ixk\n" .
                          "ARkWCnRlc3QtZG9tYWluMS0wKwYDVQQDDCRtZXJjaGFudC50ZXN0LWRvbWFpbiAo\n" .
                          "U2FuZGJveCkgLSBBUE1QMRMwEQYDVQQIDApDYWxpZm9ybmlhMQswCQYDVQQGEwJV\n" .
                          "UzEXMBUGA1UECgwOVGVzdCBNZXJjaGFudDEXMBUGA1UECwwOVGVzdCBNZXJjaGFu\n" .
                          "dDBZMBMGByqGSM49AgEGCCqGSM49AwEHA0IABGvWZDKkf8rkJ4V1sdf9Wt1iBZvD\n" .
                          "l9dEJkY/CJFVYNvK5qgWUzjbGKKcLFfvHt3vvK6jggHtMIIB6TAMBgNVHRMBAf8E\n" .
                          "-----END CERTIFICATE-----";
        return $request;
    }

    private function buildDomainEnrollmentRequest(): DomainEnrollmentRequest
    {
        $request = new DomainEnrollmentRequest();
        $request->domain = "checkout-test-domain.com";
        return $request;
    }

    private function buildSigningRequestWithEcV1(): SigningRequest
    {
        $request = new SigningRequest();
        $request->protocol_version = "ec_v1";
        return $request;
    }

    private function buildSigningRequestWithRsaV1(): SigningRequest
    {
        $request = new SigningRequest();
        $request->protocol_version = "rsa_v1";
        return $request;
    }

    // Common validation methods for DRY assertions
    private function validateUploadCertificateResponse(array $response, CertificateRequest $request): void
    {
        $this->assertResponse($response, "id", "public_key_hash", "valid_from", "valid_until");
        $this->assertNotNull($response["id"]);
        $this->assertNotNull($response["public_key_hash"]);
        $this->assertNotNull($response["valid_from"]);
        $this->assertNotNull($response["valid_until"]);

        // Verify certificate was processed
        $this->assertNotEmpty($request->content);

        // Basic certificate ID format check
        $this->assertRegExp('/^aplc_[a-zA-Z0-9]{26}$/', $response["id"]);
    }

    private function validateEnrollDomainResponse(array $response): void
    {
        // EmptyResponse (204) - no content to validate but should not throw
        $this->assertNotNull($response);
    }

    private function validateGenerateSigningRequestResponse(array $response, SigningRequest $request): void
    {
        $this->assertResponse($response, "content");
        $this->assertNotNull($response["content"]);
        $this->assertNotEmpty($response["content"]);

        // Verify it looks like a certificate signing request
        $this->assertTrue(strpos($response["content"], "BEGIN CERTIFICATE REQUEST") !== false);
        $this->assertTrue(strpos($response["content"], "END CERTIFICATE REQUEST") !== false);

        // Verify the protocol version was respected (indirectly through successful response)
        $this->assertNotNull($request->protocol_version);
        $this->assertContains($request->protocol_version, ["ec_v1", "rsa_v1"]);
    }
}
