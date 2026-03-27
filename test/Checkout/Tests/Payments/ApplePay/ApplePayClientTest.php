<?php

namespace Checkout\Tests\Payments\ApplePay;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\Payments\ApplePay\ApplePayClient;
use Checkout\Payments\ApplePay\Request\CertificateRequest;
use Checkout\Payments\ApplePay\Request\DomainEnrollmentRequest;
use Checkout\Payments\ApplePay\Request\SigningRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class ApplePayClientTest extends UnitTestFixture
{
    /**
     * @var ApplePayClient
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
        $this->client = new ApplePayClient($this->apiClient, $this->configuration);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUploadPaymentProcessingCertificate()
    {
        $request = $this->buildCertificateRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("applepay/certificates"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn(["id" => "cert_123", "public_key_hash" => "hash123"]);

        $response = $this->client->uploadPaymentProcessingCertificate($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldEnrollDomain()
    {
        $request = $this->buildDomainEnrollmentRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("applepay/enrollments"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn([]);

        $response = $this->client->enrollDomain($request);

        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldGenerateCertificateSigningRequest()
    {
        $request = $this->buildSigningRequest();

        $this->apiClient
            ->method("post")
            ->with(
                $this->equalTo("applepay/signing-requests"),
                $this->equalTo($request),
                $this->anything()
            )
            ->willReturn([
                "content" => "-----BEGIN CERTIFICATE REQUEST-----\ncertdata\n-----END CERTIFICATE REQUEST-----"
            ]);

        $response = $this->client->generateCertificateSigningRequest($request);

        $this->assertNotNull($response);
    }

    // Common builder methods for DRY setup
    private function buildCertificateRequest(): CertificateRequest
    {
        $request = new CertificateRequest();
        $request->content = "MIIBSDCB8AIBADCBjzELMAkGA1UEBhMCR0IxDzANBgNVBAgMBkxvbmRvbjEPMA0GA1UEBwwGTG9uZG9u" .
                           "MRUwEwYDVQQKDAxDaGVja291dC5jb20xCzAJBgNVBA8MAklUMRUwEwYDVQQDDAxjaGVja291dC5jb20x" .
                           "IzAhBgkqhkiG9w0BCQEWFHN1cHBvcnRAY2hlY2tvdXQuY29tMFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcD" .
                           "QgAEXf/MDFRLSblykajGm0GE+lUNoOIEa0DbWc7Bv3s55bYtW1fJo2/MJIPojUKuKUx2VsEfGmmqXKbq" .
                           "4IhAr0bM8TAKBggqhkjOPQQDAgNHADBEAiAo1Dv4TXTacSeIfy4HDjzPMQY2+OxTW6szRJjGyfKgXQIg" .
                           "dHAX0BmI+1rozysjXv8MvoxehQIGACQ+UWJle+UZ2ms=";
        return $request;
    }

    private function buildDomainEnrollmentRequest(): DomainEnrollmentRequest
    {
        $request = new DomainEnrollmentRequest();
        $request->domain = "https://example.com";
        return $request;
    }

    private function buildSigningRequest(): SigningRequest
    {
        $request = new SigningRequest();
        $request->protocol_version = "ec_v1";
        return $request;
    }
}
