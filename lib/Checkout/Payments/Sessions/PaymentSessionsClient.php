<?php

namespace Checkout\Payments\Sessions;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class PaymentSessionsClient extends Client
{

    const PAYMENT_SESSIONS = "payment-sessions";
    const SUBMIT = "submit";

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

    /**
     * @param PaymentSessionCompleteRequest $paymentSessionCompleteRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function completePaymentSession(PaymentSessionCompleteRequest $paymentSessionCompleteRequest)
    {
        return $this->apiClient->post(self::PAYMENT_SESSIONS . "/complete", $paymentSessionCompleteRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $sessionId
     * @param PaymentSessionSubmitRequest $paymentSessionSubmitRequest
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function submitPaymentSession($sessionId, PaymentSessionSubmitRequest $paymentSessionSubmitRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::PAYMENT_SESSIONS, $this->encodePathSegment($sessionId), self::SUBMIT),
            $paymentSessionSubmitRequest,
            $this->sdkAuthorization()
        );
    }

}
