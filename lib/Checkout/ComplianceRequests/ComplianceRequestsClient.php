<?php

namespace Checkout\ComplianceRequests;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\ComplianceRequests\Requests\ComplianceRequestRespondRequest;

class ComplianceRequestsClient extends Client
{
    const COMPLIANCE_REQUESTS_PATH = "compliance-requests";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * Retrieve an existing compliance request by payment ID (GET /compliance-requests/{payment_id}).
     *
     * @param string $paymentId The compliance request's payment ID. [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function getComplianceRequest(string $paymentId) : array
    {
        return $this->apiClient->get(
            $this->buildPath(self::COMPLIANCE_REQUESTS_PATH, $paymentId),
            $this->sdkAuthorization()
        );
    }

    /**
     * Submit a response to a compliance request (POST /compliance-requests/{payment_id}).
     *
     * @param string $paymentId The compliance request's payment ID. [Required]
     * @param ComplianceRequestRespondRequest $response The response details. [Required]
     * @return array
     * @throws CheckoutApiException
     */
    public function respondToComplianceRequest(
        string $paymentId,
        ComplianceRequestRespondRequest $response
    ) : array {
        return $this->apiClient->post(
            $this->buildPath(self::COMPLIANCE_REQUESTS_PATH, $paymentId),
            $response,
            $this->sdkAuthorization()
        );
    }
}
