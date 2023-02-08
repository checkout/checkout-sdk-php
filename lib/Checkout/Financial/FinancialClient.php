<?php

namespace Checkout\Financial;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class FinancialClient extends Client
{
    const FINANCIAL_ACTIONS_PATH = "financial-actions";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param FinancialActionsQuery $filter
     * @return array
     * @throws CheckoutApiException
     */
    public function query(FinancialActionsQuery $filter)
    {
        return $this->apiClient->query(self::FINANCIAL_ACTIONS_PATH, $filter, $this->sdkAuthorization());
    }
}
