<?php

namespace Checkout\StandaloneAccountUpdater;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\StandaloneAccountUpdater\Requests\GetUpdatedCardCredentialsRequest;

class StandaloneAccountUpdaterClient extends Client
{
    const ACCOUNT_UPDATER_PATH = "account-updater";
    const CARDS_PATH = "cards";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param GetUpdatedCardCredentialsRequest $getUpdatedCardCredentialsRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function getUpdatedCardCredentials(GetUpdatedCardCredentialsRequest $getUpdatedCardCredentialsRequest) : array
    {
        return $this->apiClient->post(
            $this->buildPath(self::ACCOUNT_UPDATER_PATH, self::CARDS_PATH),
            $getUpdatedCardCredentialsRequest,
            $this->sdkAuthorization()
        );
    }
}
