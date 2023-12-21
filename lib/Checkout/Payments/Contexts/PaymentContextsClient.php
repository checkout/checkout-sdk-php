<?php

namespace Checkout\Payments\Contexts;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class PaymentContextsClient extends Client
{

    const PAYMENT_CONTEXTS = "payment-contexts";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param PaymentContextsRequest $paymentContextsRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createPaymentContexts(PaymentContextsRequest $paymentContextsRequest)
    {
        return $this->apiClient->post(self::PAYMENT_CONTEXTS, $paymentContextsRequest, $this->sdkAuthorization());
    }

    /**
     * @param $id
     * @return array
     * @throws CheckoutApiException
     */
    public function getPaymentContextDetails($id)
    {
        return $this->apiClient->get($this->buildPath(self::PAYMENT_CONTEXTS, $id), $this->sdkAuthorization());
    }

}
