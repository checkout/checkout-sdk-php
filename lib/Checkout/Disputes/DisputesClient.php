<?php

namespace Checkout\Disputes;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Files\FilesClient;

class DisputesClient extends FilesClient
{
    protected const DISPUTES_PATH = "disputes";
    private const ACCEPT_PATH = "accept";
    private const EVIDENCE_PATH = "evidence";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param DisputesQueryFilter $filter
     * @return mixed
     * @throws CheckoutApiException
     */
    public function query(DisputesQueryFilter $filter)
    {
        return $this->apiClient->query(self::DISPUTES_PATH, $filter, $this->sdkAuthorization());
    }

    /**
     * @param string $disputeId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getDisputeDetails(string $disputeId)
    {
        return $this->apiClient->get($this->buildPath(self::DISPUTES_PATH, $disputeId), $this->sdkAuthorization());
    }

    /**
     * @param string $disputeId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function accept(string $disputeId)
    {
        return $this->apiClient->post($this->buildPath(self::DISPUTES_PATH, $disputeId, self::ACCEPT_PATH), null, $this->sdkAuthorization());
    }

    /**
     * @param string $disputeId
     * @param DisputeEvidenceRequest $disputeEvidenceRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function putEvidence(string $disputeId, DisputeEvidenceRequest $disputeEvidenceRequest)
    {
        return $this->apiClient->put($this->buildPath(self::DISPUTES_PATH, $disputeId, self::EVIDENCE_PATH), $disputeEvidenceRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $disputeId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getEvidence(string $disputeId)
    {
        return $this->apiClient->get($this->buildPath(self::DISPUTES_PATH, $disputeId, self::EVIDENCE_PATH), $this->sdkAuthorization());
    }

    /**
     * @param string $disputeId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function submitEvidence(string $disputeId)
    {
        return $this->apiClient->post($this->buildPath(self::DISPUTES_PATH, $disputeId, self::EVIDENCE_PATH), null, $this->sdkAuthorization());
    }

}
