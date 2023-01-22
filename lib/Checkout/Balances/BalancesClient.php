<?php

namespace Checkout\Balances;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class BalancesClient extends Client
{
    const BALANCES_PATH = "balances";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param $entityId
     * @param BalancesQuery $balancesQuery
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEntityBalances($entityId, BalancesQuery $balancesQuery)
    {
        return $this->apiClient->query(
            $this->buildPath(self::BALANCES_PATH, $entityId),
            $balancesQuery,
            $this->sdkAuthorization()
        );
    }
}
