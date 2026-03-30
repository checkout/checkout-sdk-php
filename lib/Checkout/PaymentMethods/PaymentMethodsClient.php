<?php

namespace Checkout\PaymentMethods;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

use Checkout\PaymentMethods\Requests\PaymentMethodsQuery;

class PaymentMethodsClient extends Client
{
    const PAYMENT_METHODS_PATH = "payment-methods";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * Get available payment methods
     *
     * Get a list of all available payment methods for a specific Processing Channel ID.
     *
     * @param PaymentMethodsQuery $query Query parameters including processing_channel_id (Required)
     * @return array
     * @throws CheckoutApiException
     */
    public function getAvailablePaymentMethods(PaymentMethodsQuery $query): array
    {
        return $this->apiClient->query(
            self::PAYMENT_METHODS_PATH,
            $query,
            $this->sdkAuthorization()
        );
    }
}
