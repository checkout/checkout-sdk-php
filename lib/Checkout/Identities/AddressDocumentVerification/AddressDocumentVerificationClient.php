<?php

namespace Checkout\Identities\AddressDocumentVerification;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Identities\Entities\AttemptAssetsQueryFilter;
use Checkout\Identities\Entities\AttemptsQueryFilter;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationRequest;
use Checkout\Identities\AddressDocumentVerification\Requests\AddressDocumentVerificationAttemptRequest;

class AddressDocumentVerificationClient extends Client
{
    const ADDRESS_DOCUMENT_VERIFICATIONS_PATH = "address-document-verifications";
    const ANONYMIZE_PATH = "anonymize";
    const ATTEMPTS_PATH = "attempts";
    const ASSETS_PATH = "assets";
    const PDF_REPORT_PATH = "pdf-report";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param AddressDocumentVerificationRequest $addressDocumentVerificationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createAddressDocumentVerification(
        AddressDocumentVerificationRequest $addressDocumentVerificationRequest
    ): array {
        return $this->apiClient->post(
            self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
            $addressDocumentVerificationRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getAddressDocumentVerification(string $addressDocumentVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH, $addressDocumentVerificationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function anonymizeAddressDocumentVerification(string $addressDocumentVerificationId): array
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
                $addressDocumentVerificationId,
                self::ANONYMIZE_PATH
            ),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @param AddressDocumentVerificationAttemptRequest $addressDocumentVerificationAttemptRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createAddressDocumentVerificationAttempt(
        string $addressDocumentVerificationId,
        AddressDocumentVerificationAttemptRequest $addressDocumentVerificationAttemptRequest
    ): array {
        return $this->apiClient->post(
            $this->buildPath(
                self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
                $addressDocumentVerificationId,
                self::ATTEMPTS_PATH
            ),
            $addressDocumentVerificationAttemptRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Get the details of all attempts for a specific address document verification.
     * Results are paginated. Use the skip and limit query parameters to page through them.
     * Beta.
     *
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @param AttemptsQueryFilter|null $query the pagination query parameters (skip and limit)
     * @return array
     * @throws CheckoutApiException
     */
    public function getAddressDocumentVerificationAttempts(
        string $addressDocumentVerificationId,
        ?AttemptsQueryFilter $query = null
    ): array {
        return $this->apiClient->query(
            $this->buildPath(
                self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
                $addressDocumentVerificationId,
                self::ATTEMPTS_PATH
            ),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @param string $attemptId
     * @return array
     * @throws CheckoutApiException
     */
    public function getAddressDocumentVerificationAttempt(
        string $addressDocumentVerificationId,
        string $attemptId
    ): array {
        return $this->apiClient->get(
            $this->buildPath(
                self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
                $addressDocumentVerificationId,
                self::ATTEMPTS_PATH,
                $attemptId
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function getAddressDocumentVerificationReport(string $addressDocumentVerificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
                $addressDocumentVerificationId,
                self::PDF_REPORT_PATH
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Get the assets (the document image) uploaded for an address document verification attempt.
     * Results are paginated. Use the skip and limit query parameters to page through them.
     * Beta.
     *
     * addressDocumentVerificationId is the address document verification's unique identifier. (Required)
     * attemptId is the attempt's unique identifier. (Required)
     *
     * @param string $addressDocumentVerificationId
     * @param string $attemptId
     * @param AttemptAssetsQueryFilter|null $query the pagination query parameters (skip and limit)
     * @return array
     * @throws CheckoutApiException
     */
    public function getAddressDocumentVerificationAttemptAssets(
        string $addressDocumentVerificationId,
        string $attemptId,
        ?AttemptAssetsQueryFilter $query = null
    ): array {
        return $this->apiClient->query(
            $this->buildPath(
                self::ADDRESS_DOCUMENT_VERIFICATIONS_PATH,
                $addressDocumentVerificationId,
                self::ATTEMPTS_PATH,
                $attemptId,
                self::ASSETS_PATH
            ),
            $query,
            $this->sdkAuthorization()
        );
    }
}
