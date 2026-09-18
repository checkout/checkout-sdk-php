<?php

namespace Checkout\Identities\IdentityVerification;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Identities\Entities\AttemptsQueryFilter;
use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAndOpenRequest;
use Checkout\Identities\IdentityVerification\Requests\IdentityVerificationAttemptRequest;

class IdentityVerificationClient extends Client
{
    const IDENTITY_VERIFICATIONS_PATH = "identity-verifications";
    const CREATE_AND_OPEN_IDV_PATH = "create-and-open-idv";
    const ANONYMIZE_PATH = "anonymize";
    const ATTEMPTS_PATH = "attempts";
    const PDF_REPORT_PATH = "pdf-report";
    const ASSETS_PATH = "assets";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param IdentityVerificationAndOpenRequest $identityVerificationAndOpenRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createIdentityVerificationAndAttempt(
        IdentityVerificationAndOpenRequest $identityVerificationAndOpenRequest
    ): array {
        return $this->apiClient->post(
            self::CREATE_AND_OPEN_IDV_PATH,
            $identityVerificationAndOpenRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param IdentityVerificationRequest $identityVerificationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createIdentityVerification(IdentityVerificationRequest $identityVerificationRequest): array
    {
        return $this->apiClient->post(
            self::IDENTITY_VERIFICATIONS_PATH,
            $identityVerificationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * identityVerificationId is the identity verification's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdentityVerification(string $identityVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * identityVerificationId is the identity verification's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function anonymizeIdentityVerification(string $identityVerificationId): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId, self::ANONYMIZE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

     /**
     * identityVerificationId is the identity verification's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @param IdentityVerificationAttemptRequest $identityVerificationAttemptRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createIdentityVerificationAttempt(
        string $identityVerificationId,
        IdentityVerificationAttemptRequest $identityVerificationAttemptRequest
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId, self::ATTEMPTS_PATH),
            $identityVerificationAttemptRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get all the attempts for a specific identity verification.
     * Results are paginated. Use the skip and limit query parameters to page through them.
     * Beta.
     *
     * identityVerificationId is the identity verification's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @param AttemptsQueryFilter|null $query the pagination query parameters (skip and limit)
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdentityVerificationAttempts(
        string $identityVerificationId,
        ?AttemptsQueryFilter $query = null
    ): array {
        return $this->apiClient->query(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId, self::ATTEMPTS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * identityVerificationId is the identity verification's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @param string $attemptId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdentityVerificationAttempt(string $identityVerificationId, string $attemptId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId, self::ATTEMPTS_PATH, $attemptId),
            $this->sdkAuthorization()
        );
    }

    /**
     * identityVerificationId is the identity verification's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdentityVerificationPdfReport(string $identityVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId, self::PDF_REPORT_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * Get the assets (face images, videos, and document images) captured during an identity
     * verification attempt. Videos are not exposed by default; contact your account manager to
     * enable them.
     * Results are paginated. Use the skip and limit query parameters to page through them.
     * Beta.
     *
     * identityVerificationId is the identity verification's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $identityVerificationId
     * @param string $attemptId
     * @param AttemptAssetsQueryFilter|null $query the pagination query parameters (skip and limit)
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdentityVerificationAttemptAssets(
        string $identityVerificationId,
        string $attemptId,
        ?AttemptAssetsQueryFilter $query = null
    ): array {
        return $this->apiClient->query(
            $this->buildPath(self::IDENTITY_VERIFICATIONS_PATH, $identityVerificationId, self::ATTEMPTS_PATH, $attemptId, self::ASSETS_PATH),
            $query,
            $this->sdkAuthorization()
        );
    }
}
