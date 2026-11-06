<?php

namespace Checkout\Customers;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\Client;

class CustomersClient extends Client
{
    const CUSTOMERS_PATH = "customers";

    /**
     * @param $customerId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function get($customerId)
    {
        return $this->apiClient->get($this->buildPath(
            self::CUSTOMERS_PATH,
            $this->encodePathSegment($customerId)
        ), $this->sdkAuthorization());
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
     * @throws CheckoutArgumentException
     */
    public function update($customerId, CustomerRequest $customerRequest)
    {
        return $this->apiClient->patch($this->buildPath(
            self::CUSTOMERS_PATH,
            $this->encodePathSegment($customerId)
        ), $customerRequest, $this->sdkAuthorization());
    }

    /**
     * @param $customerId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function delete($customerId)
    {
        return $this->apiClient->delete($this->buildPath(
            self::CUSTOMERS_PATH,
            $this->encodePathSegment($customerId)
        ), $this->sdkAuthorization());
    }
}
