<?php

namespace Checkout\Payments\GooglePay;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\GooglePay\Requests\GooglePayEnrollmentRequest;
use Checkout\Payments\GooglePay\Requests\GooglePayRegisterDomainRequest;

class GooglePayClient extends Client
{
    const GOOGLEPAY_PATH = "googlepay";
    const ENROLLMENTS_PATH = "enrollments";
    const DOMAIN_PATH = "domain";
    const DOMAINS_PATH = "domains";
    const STATE_PATH = "state";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$oAuth);
    }

    /**
     * Enroll an entity to the Google Pay service (POST /googlepay/enrollments).
     * OAuth scope: vault:gpayme-enrollment (see API reference).
     *
     * @param GooglePayEnrollmentRequest $request [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function createEnrollment(GooglePayEnrollmentRequest $request) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::GOOGLEPAY_PATH, self::ENROLLMENTS_PATH),
            $request,
            $this->sdkAuthorization()
        );
    }

    /**
     * Associates a web domain with the specified enrolled entity (POST /googlepay/enrollments/{entity_id}/domain).
     *
     * @param string $entityId Unique identifier of the entity. [Required]
     * @param GooglePayRegisterDomainRequest $request [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function registerDomain(string $entityId, GooglePayRegisterDomainRequest $request) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::GOOGLEPAY_PATH, self::ENROLLMENTS_PATH, $entityId, self::DOMAIN_PATH),
            $request,
            $this->sdkAuthorization()
        );
    }

    /**
     * Retrieves all web domains registered for the specified entity (GET /googlepay/enrollments/{entity_id}/domains).
     *
     * @param string $entityId Unique identifier of the entity. [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function getRegisteredDomains(string $entityId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::GOOGLEPAY_PATH, self::ENROLLMENTS_PATH, $entityId, self::DOMAINS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * Returns the current enrollment state of an entity (GET /googlepay/enrollments/{entity_id}/state).
     *
     * @param string $entityId Unique identifier of the entity. [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function getEnrollmentState(string $entityId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::GOOGLEPAY_PATH, self::ENROLLMENTS_PATH, $entityId, self::STATE_PATH),
            $this->sdkAuthorization()
        );
    }
}
