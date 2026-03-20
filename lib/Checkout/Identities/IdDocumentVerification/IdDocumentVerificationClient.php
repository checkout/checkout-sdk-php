<?php

namespace Checkout\Identities\IdDocumentVerification;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Identities\IdDocumentVerification\Requests\IdDocumentVerificationRequest;
use Checkout\Identities\IdDocumentVerification\Requests\IdDocumentVerificationAttemptRequest;

class IdDocumentVerificationClient extends Client
{
    const ID_DOCUMENT_VERIFICATIONS_PATH = "id-document-verifications";
    const ANONYMIZE_PATH = "anonymize";
    const ATTEMPTS_PATH = "attempts";
    const PDF_REPORT_PATH = "pdf-report";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param IdDocumentVerificationRequest $idDocumentVerificationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createIdDocumentVerification(IdDocumentVerificationRequest $idDocumentVerificationRequest): array
    {
        return $this->apiClient->post(
            self::ID_DOCUMENT_VERIFICATIONS_PATH,
            $idDocumentVerificationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * idDocumentVerificationId is the ID document verification's unique identifier. (Required)
     *
     * @param string $idDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdDocumentVerification(string $idDocumentVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ID_DOCUMENT_VERIFICATIONS_PATH, $idDocumentVerificationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * idDocumentVerificationId is the ID document verification's unique identifier. (Required)
     *
     * @param string $idDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function anonymizeIdDocumentVerification(string $idDocumentVerificationId): array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ID_DOCUMENT_VERIFICATIONS_PATH, $idDocumentVerificationId, self::ANONYMIZE_PATH),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * idDocumentVerificationId is the ID document verification's unique identifier. (Required)
     *
     * @param string $idDocumentVerificationId
     * @param IdDocumentVerificationAttemptRequest $idDocumentVerificationAttemptRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createIdDocumentVerificationAttempt(
        string $idDocumentVerificationId,
        IdDocumentVerificationAttemptRequest $idDocumentVerificationAttemptRequest
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::ID_DOCUMENT_VERIFICATIONS_PATH, $idDocumentVerificationId, self::ATTEMPTS_PATH),
            $idDocumentVerificationAttemptRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * idDocumentVerificationId is the ID document verification's unique identifier. (Required)
     *
     * @param string $idDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdDocumentVerificationAttempts(string $idDocumentVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ID_DOCUMENT_VERIFICATIONS_PATH, $idDocumentVerificationId, self::ATTEMPTS_PATH),
            $this->sdkAuthorization()
        );
    }

    /**
     * idDocumentVerificationId is the ID document verification's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $idDocumentVerificationId
     * @param string $attemptId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdDocumentVerificationAttempt(string $idDocumentVerificationId, string $attemptId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ID_DOCUMENT_VERIFICATIONS_PATH, $idDocumentVerificationId, self::ATTEMPTS_PATH, $attemptId),
            $this->sdkAuthorization()
        );
    }

    /**
     * idDocumentVerificationId is the ID document verification's unique identifier. (Required)
     *
     * @param string $idDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getIdDocumentVerificationReport(string $idDocumentVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ID_DOCUMENT_VERIFICATIONS_PATH, $idDocumentVerificationId, self::PDF_REPORT_PATH),
            $this->sdkAuthorization()
        );
    }
}
