<?php

namespace Checkout\Accounts;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Files\FilesClient;

class AccountsClient extends FilesClient
{
    const ACCOUNTS_PATH = "accounts";
    const INSTRUMENT_PATH = "instruments";
    const FILES_PATH = "files";
    const ENTITIES_PATH = "entities";
    const PAYOUT_SCHEDULES_PATH = "payout-schedules";

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
}
