<?php

namespace Checkout\Customers;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class CustomersClient extends Client
{
    private const CUSTOMERS_PATH = "customers";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param string $customerId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function get(string $customerId)
    {
        return $this->apiClient->get($this->buildPath(self::CUSTOMERS_PATH, $customerId), $this->sdkAuthorization());
    }

    /**
     * @param CustomerRequest $customerRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function create(CustomerRequest $customerRequest)
    {
        return $this->apiClient->post(self::CUSTOMERS_PATH, $customerRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $customerId
     * @param CustomerRequest $customerRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function update(string $customerId, CustomerRequest $customerRequest)
    {
        return $this->apiClient->patch($this->buildPath(self::CUSTOMERS_PATH, $customerId), $customerRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $customerId
     * @throws CheckoutApiException
     */
    public function delete(string $customerId): void
    {
        $this->apiClient->delete($this->buildPath(self::CUSTOMERS_PATH, $customerId), $this->sdkAuthorization());
    }

}
