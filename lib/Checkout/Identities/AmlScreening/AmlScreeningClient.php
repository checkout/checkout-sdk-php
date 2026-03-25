<?php

namespace Checkout\Identities\AmlScreening;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Identities\AmlScreening\Requests\AmlScreeningRequest;

class AmlScreeningClient extends Client
{
    const AML_VERIFICATIONS_PATH = "aml-verifications";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param AmlScreeningRequest $amlScreeningRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createAmlScreening(AmlScreeningRequest $amlScreeningRequest): array
    {
        return $this->apiClient->post(
            self::AML_VERIFICATIONS_PATH,
            $amlScreeningRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * amlVerificationId is the AML screening's unique identifier. (Required)
     *
     * @param string $amlVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getAmlScreening(string $amlVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::AML_VERIFICATIONS_PATH, $amlVerificationId),
            $this->sdkAuthorization()
        );
    }
}
