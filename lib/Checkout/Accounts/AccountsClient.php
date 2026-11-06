<?php

namespace Checkout\Accounts;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Files\FilesClient;
use Checkout\Accounts\ReserveRules\Requests\CreateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Requests\UpdateReserveRuleRequest;
use Checkout\Accounts\Files\Requests\UploadFileRequest;
use Checkout\Accounts\Headers;

class AccountsClient extends Client
{
    const ACCOUNTS_PATH = "accounts";
    const INSTRUMENT_PATH = "instruments";
    const FILES_PATH = "files";
    const ENTITIES_PATH = "entities";
    const PAYOUT_SCHEDULES_PATH = "payout-schedules";
    const PAYMENT_INSTRUMENTS_PATH = "payment-instruments";
    const MEMBERS_PATH = "members";
    const RESERVE_RULES_PATH = "reserve-rules";
    const REQUIREMENTS_PATH = "requirements";

    /**
     * The latest Accounts API schema version, negotiated through the Accept header.
     */
    const DEFAULT_SCHEMA_VERSION = "3.0";

    private $filesApiClient;

    public function __construct(
        ApiClient             $apiClient,
        ApiClient             $filesApiClient,
        CheckoutConfiguration $configuration
    ) {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
        $this->filesApiClient = $filesApiClient;
    }

    /**
     * Build the Accept header used to negotiate the Accounts API schema version.
     *
     * @param string $schemaVersion
     * @return Headers
     */
    private function buildSchemaVersionHeaders($schemaVersion)
    {
        $headers = new Headers();
        $headers->accept = "application/json;schema_version=" . $schemaVersion;
        return $headers;
    }

    /**
     * @param OnboardEntityRequest $entityRequest
     * @param string $schemaVersion The Accounts API schema version to request (default: latest, 3.0)
     * @return array
     * @throws CheckoutApiException
     */
    public function createEntity(OnboardEntityRequest $entityRequest, $schemaVersion = self::DEFAULT_SCHEMA_VERSION)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH),
            $entityRequest,
            $this->sdkAuthorization(),
            null,
            $this->buildSchemaVersionHeaders($schemaVersion)
        );
    }

    /**
     * @param string $entityId
     * @param string $paymentInstrumentId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retrievePaymentInstrumentDetails($entityId, $paymentInstrumentId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::PAYMENT_INSTRUMENTS_PATH,
                $this->encodePathSegment($paymentInstrumentId)
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $entityId
     * @param string $schemaVersion The Accounts API schema version to request (default: latest, 3.0)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getEntity($entityId, $schemaVersion = self::DEFAULT_SCHEMA_VERSION)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $this->encodePathSegment($entityId)),
            $this->sdkAuthorization(),
            $this->buildSchemaVersionHeaders($schemaVersion)
        );
    }

    /**
     * @param $entityId
     * @param OnboardEntityRequest $entityRequest
     * @param string $schemaVersion The Accounts API schema version to request (default: latest, 3.0)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function updateEntity(
        $entityId,
        OnboardEntityRequest $entityRequest,
        $schemaVersion = self::DEFAULT_SCHEMA_VERSION
    ) {
        return $this->apiClient->put(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $this->encodePathSegment($entityId)),
            $entityRequest,
            $this->sdkAuthorization(),
            $this->buildSchemaVersionHeaders($schemaVersion)
        );
    }

    /**
     * @param $entityId
     * @param AccountsPaymentInstrument $accountsPaymentInstrument
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     * @deprecated Use {@link createBankPaymentInstrument} instead
     */
    public function createPaymentInstrument($entityId, AccountsPaymentInstrument $accountsPaymentInstrument)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::INSTRUMENT_PATH
            ),
            $accountsPaymentInstrument,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @param PaymentInstrumentRequest $instrumentRequest
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function createBankPaymentInstrument($entityId, PaymentInstrumentRequest $instrumentRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::PAYMENT_INSTRUMENTS_PATH
            ),
            $instrumentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @param string $instrumentId
     * @param UpdatePaymentInstrumentRequest $instrumentRequest
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function updateBankPaymentInstrumentDetails(
        $entityId,
        $instrumentId,
        UpdatePaymentInstrumentRequest $instrumentRequest
    ) {
        // The API reads the ETag only from the If-Match HTTP header and answers 428 without it, so the
        // request's headers are sent as HTTP headers.
        return $this->apiClient->patch(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::PAYMENT_INSTRUMENTS_PATH,
                $this->encodePathSegment($instrumentId)
            ),
            $instrumentRequest,
            $this->sdkAuthorization(),
            $instrumentRequest->headers
        );
    }

    /**
     * @param string $entityId
     * @param PaymentInstrumentsQuery $query
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function queryPaymentInstruments($entityId, PaymentInstrumentsQuery $query)
    {
        return $this->apiClient->query(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::PAYMENT_INSTRUMENTS_PATH
            ),
            $query,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param AccountsFileRequest $accountsFileRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function submitFile(AccountsFileRequest $accountsFileRequest)
    {
        return $this->filesApiClient->submitFileFilesApi(
            self::FILES_PATH,
            $accountsFileRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @param string $currency
     * @param UpdateScheduleRequest $updateScheduleRequest
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function updatePayoutSchedule($entityId, $currency, UpdateScheduleRequest $updateScheduleRequest)
    {
        return $this->apiClient->put(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::PAYOUT_SCHEDULES_PATH
            ),
            [$currency => $updateScheduleRequest],
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retrievePayoutSchedule($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::PAYOUT_SCHEDULES_PATH
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Get sub-entity Members
     *
     * Retrieve information on all users of a sub-entity that has been invited through Hosted Onboarding.
     *
     * @param string $entityId The ID of the sub-entity (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getSubEntityMembers($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::MEMBERS_PATH
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Reinvite a sub-entity member
     *
     * Resend an invitation to the user of a sub-entity.
     *
     * @param string $entityId The ID of the sub-entity (Required)
     * @param string $userId The ID of the invited user (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function reinviteSubEntityMember($entityId, $userId)
    {
        return $this->apiClient->put(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::MEMBERS_PATH,
                $this->encodePathSegment($userId)
            ),
            null,
            $this->sdkAuthorization()
        );
    }

    /**
     * Add a reserve rule
     *
     * Create a sub-entity reserve rule.
     *
     * @param string $entityId The sub-entity's ID
     * @param CreateReserveRuleRequest $createRequest The create request
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function createReserveRule($entityId, CreateReserveRuleRequest $createRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::RESERVE_RULES_PATH
            ),
            $createRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Query reserve rules
     *
     * Fetch all of the reserve rules for a sub-entity.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getReserveRules($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::RESERVE_RULES_PATH
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Get reserve rule details
     *
     * Retrieve the details of a specific reserve rule.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param string $reserveRuleId The reserve rule ID (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getReserveRuleDetails($entityId, $reserveRuleId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::RESERVE_RULES_PATH,
                $this->encodePathSegment($reserveRuleId)
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Update reserve rule
     *
     * Update an upcoming reserve rule. Only reserve rules that have never been active can be updated.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param string $reserveRuleId The reserve rule ID (Required)
     * @param string $etag The ETag value for safe update (Required)
     * @param UpdateReserveRuleRequest $updateRequest The update request (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function updateReserveRule($entityId, $reserveRuleId, $etag, UpdateReserveRuleRequest $updateRequest)
    {
        $headers = null;
        if ($etag !== null) {
            $headers = new Headers();
            $headers->if_match = $etag;
        }
        
        return $this->apiClient->put(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::RESERVE_RULES_PATH,
                $this->encodePathSegment($reserveRuleId)
            ),
            $updateRequest,
            $this->sdkAuthorization(),
            $headers
        );
    }

    /**
     * Upload a file
     *
     * Upload documentation required for full due diligence for a sub-entity.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param UploadFileRequest $fileRequest The file upload request (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function uploadFile($entityId, UploadFileRequest $fileRequest)
    {
        return $this->filesApiClient->post(
            $this->buildPath(self::ENTITIES_PATH, $this->encodePathSegment($entityId), self::FILES_PATH),
            $fileRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * Retrieve a file
     *
     * Retrieve information about a previously uploaded file for a sub-entity.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param string $fileId The file ID (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retrieveFile($entityId, $fileId)
    {
        return $this->filesApiClient->get(
            $this->buildPath(
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::FILES_PATH,
                $this->encodePathSegment($fileId)
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Get entity requirements
     *
     * Retrieve the list of pending requirements for a sub-entity.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param string $schemaVersion The Accounts API schema version to request (default: latest, 3.0)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getEntityRequirements($entityId, $schemaVersion = self::DEFAULT_SCHEMA_VERSION)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::REQUIREMENTS_PATH
            ),
            $this->sdkAuthorization(),
            $this->buildSchemaVersionHeaders($schemaVersion)
        );
    }

    /**
     * Get entity requirement details
     *
     * Retrieve detailed information about a specific requirement.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param string $requirementId The requirement ID (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getEntityRequirementDetails($entityId, $requirementId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::REQUIREMENTS_PATH,
                $this->encodePathSegment($requirementId)
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * Resolve entity requirement
     *
     * Submit a response to resolve a pending requirement.
     *
     * @param string $entityId The sub-entity's ID (Required)
     * @param string $requirementId The requirement ID (Required)
     * @param EntityRequirementUpdateRequest $request The requirement resolution request (Required)
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function resolveEntityRequirement($entityId, $requirementId, EntityRequirementUpdateRequest $request)
    {
        return $this->apiClient->put(
            $this->buildPath(
                self::ACCOUNTS_PATH,
                self::ENTITIES_PATH,
                $this->encodePathSegment($entityId),
                self::REQUIREMENTS_PATH,
                $this->encodePathSegment($requirementId)
            ),
            $request,
            $this->sdkAuthorization()
        );
    }
}
