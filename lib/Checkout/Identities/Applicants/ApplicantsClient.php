<?php

namespace Checkout\Identities\Applicants;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Identities\Applicants\Requests\ApplicantRequest;
use Checkout\Identities\Applicants\Requests\ApplicantUpdateRequest;

class ApplicantsClient extends Client
{
    const APPLICANTS_PATH = "applicants";
    const ANONYMIZE_PATH = "anonymize";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param ApplicantRequest $applicantRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createApplicant(ApplicantRequest $applicantRequest): array
    {
        return $this->apiClient->post(
            self::APPLICANTS_PATH,
            $applicantRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * applicantId is the applicant profile's unique identifier. (Required)
     *
     * @param string $applicantId
     * @return array
     * @throws CheckoutApiException
     */
    public function getApplicant(string $applicantId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::APPLICANTS_PATH, $applicantId),
            $this->sdkAuthorization()
        );
    }

    /**
     * applicantId is the applicant profile's unique identifier. (Required)
     *
     * @param string $applicantId
     * @param ApplicantUpdateRequest $applicantUpdateRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateApplicant(string $applicantId, ApplicantUpdateRequest $applicantUpdateRequest): array
    {
        return $this->apiClient->patch(
            $this->buildPath(self::APPLICANTS_PATH, $applicantId),
            $applicantUpdateRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * applicantId is the applicant profile's unique identifier. (Required)
     *
     * @param string $applicantId
     * @return array
     * @throws CheckoutApiException
     */
    public function anonymizeApplicant(string $applicantId): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::APPLICANTS_PATH, $applicantId, self::ANONYMIZE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }
}
