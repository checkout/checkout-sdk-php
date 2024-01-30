<?php

namespace Checkout\Payments\Sessions;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class PaymentSessionsClient extends Client
{

    const PAYMENT_SESSIONS = "payment-sessions";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param PaymentSessionsRequest $paymentSessionsRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createPaymentSessions(PaymentSessionsRequest $paymentSessionsRequest)
    {
        return $this->apiClient->post(self::PAYMENT_SESSIONS, $paymentSessionsRequest, $this->sdkAuthorization());
    }

}
