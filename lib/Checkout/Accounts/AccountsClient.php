<?php

namespace Checkout\Accounts;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Files\FilesClient;
use Checkout\Accounts\ReserveRules\Requests\CreateReserveRuleRequest;
use Checkout\Accounts\ReserveRules\Requests\UpdateReserveRuleRequest;
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
     * @param OnboardEntityRequest $entityRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createEntity(OnboardEntityRequest $entityRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH),
            $entityRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @param string $paymentInstrumentId
     * @return array
     * @throws CheckoutApiException
     */
    public function retrievePaymentInstrumentDetails($entityId, $paymentInstrumentId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::PAYMENT_INSTRUMENTS_PATH, $paymentInstrumentId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $entityId
     * @return array
     * @throws CheckoutApiException
     */
    public function getEntity($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $entityId
     * @param OnboardEntityRequest $entityRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updateEntity($entityId, OnboardEntityRequest $entityRequest)
    {
        return $this->apiClient->put(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId),
            $entityRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $entityId
     * @param AccountsPaymentInstrument $accountsPaymentInstrument
     * @return array
     * @throws CheckoutApiException
     * @deprecated Use {@link createBankPaymentInstrument} instead
     */
    public function createPaymentInstrument($entityId, AccountsPaymentInstrument $accountsPaymentInstrument)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::INSTRUMENT_PATH),
            $accountsPaymentInstrument,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @param PaymentInstrumentRequest $instrumentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createBankPaymentInstrument($entityId, PaymentInstrumentRequest $instrumentRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::PAYMENT_INSTRUMENTS_PATH),
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
     */
    public function updateBankPaymentInstrumentDetails(
        $entityId,
        $instrumentId,
        UpdatePaymentInstrumentRequest $instrumentRequest
    ) {
        return $this->apiClient->patch(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::PAYMENT_INSTRUMENTS_PATH, $instrumentId),
            $instrumentRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @param PaymentInstrumentsQuery $query
     * @return array
     * @throws CheckoutApiException
     */
    public function queryPaymentInstruments($entityId, PaymentInstrumentsQuery $query)
    {
        return $this->apiClient->query(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::PAYMENT_INSTRUMENTS_PATH),
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
     */
    public function updatePayoutSchedule($entityId, $currency, UpdateScheduleRequest $updateScheduleRequest)
    {
        return $this->apiClient->put(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::PAYOUT_SCHEDULES_PATH),
            [$currency => $updateScheduleRequest],
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $entityId
     * @return array
     * @throws CheckoutApiException
     */
    public function retrievePayoutSchedule($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::PAYOUT_SCHEDULES_PATH),
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
     */
    public function getSubEntityMembers($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::MEMBERS_PATH),
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
     */
    public function reinviteSubEntityMember($entityId, $userId)
    {
        return $this->apiClient->put(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::MEMBERS_PATH, $userId),
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
     */
    public function createReserveRule($entityId, CreateReserveRuleRequest $createRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::RESERVE_RULES_PATH),
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
     */
    public function getReserveRules($entityId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::RESERVE_RULES_PATH),
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
     */
    public function getReserveRuleDetails($entityId, $reserveRuleId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::RESERVE_RULES_PATH, $reserveRuleId),
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
     */
    public function updateReserveRule($entityId, $reserveRuleId, $etag, UpdateReserveRuleRequest $updateRequest)
    {
        $headers = null;
        if ($etag !== null) {
            $headers = new Headers();
            $headers->if_match = $etag;
        }
        
        return $this->apiClient->put(
            $this->buildPath(self::ACCOUNTS_PATH, self::ENTITIES_PATH, $entityId, self::RESERVE_RULES_PATH, $reserveRuleId),
            $updateRequest,
            $this->sdkAuthorization(),
            $headers
        );
    }
}
