<?php

namespace Checkout\Customers\Four;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class CustomersClient extends Client
{
    const CUSTOMERS_PATH = "customers";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param $customerId
     * @return array
     * @throws CheckoutApiException
     */
    public function get($customerId)
    {
        return $this->apiClient->get($this->buildPath(self::CUSTOMERS_PATH, $customerId), $this->sdkAuthorization());
    }

    /**
     * @param CustomerRequest $customerRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function create(CustomerRequest $customerRequest)
    {
        return $this->apiClient->post(self::CUSTOMERS_PATH, $customerRequest, $this->sdkAuthorization());
    }

    /**
     * @param $customerId
     * @param CustomerRequest $customerRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function update($customerId, CustomerRequest $customerRequest)
    {
        return $this->apiClient->patch($this->buildPath(self::CUSTOMERS_PATH, $customerId), $customerRequest, $this->sdkAuthorization());
    }

    /**
     * @param $customerId
     * @return array
     * @throws CheckoutApiException
     */
    public function delete($customerId)
    {
        return $this->apiClient->delete($this->buildPath(self::CUSTOMERS_PATH, $customerId), $this->sdkAuthorization());
    }
}
