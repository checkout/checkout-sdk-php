<?php

namespace Checkout\Payments\ApplePay;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\ApplePay\Request\CertificateRequest;
use Checkout\Payments\ApplePay\Request\DomainEnrollmentRequest;
use Checkout\Payments\ApplePay\Request\SigningRequest;

class ApplePayClient extends Client
{
    const APPLEPAY_PATH = "applepay";
    const CERTIFICATES_PATH = "certificates";
    const ENROLLMENTS_PATH = "enrollments";
    const SIGNING_REQUESTS_PATH = "signing-requests";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * Upload a payment processing certificate. This will allow you to start processing payments via Apple Pay.
     *
     * @param CertificateRequest $certificateRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function uploadPaymentProcessingCertificate(CertificateRequest $certificateRequest): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::APPLEPAY_PATH, self::CERTIFICATES_PATH),
            $certificateRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Enroll a domain to the Apple Pay Service
     *
     * @param DomainEnrollmentRequest $domainEnrollmentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function enrollDomain(DomainEnrollmentRequest $domainEnrollmentRequest): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::APPLEPAY_PATH, self::ENROLLMENTS_PATH),
            $domainEnrollmentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Generate a certificate signing request. You'll need to upload this to your Apple Developer
     * account to download a payment processing certificate.
     *
     * @param SigningRequest $signingRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function generateCertificateSigningRequest(SigningRequest $signingRequest): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::APPLEPAY_PATH, self::SIGNING_REQUESTS_PATH),
            $signingRequest,
            $this->sdkAuthorization()
        );
    }
}
