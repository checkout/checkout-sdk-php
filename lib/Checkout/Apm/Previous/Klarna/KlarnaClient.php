<?php

namespace Checkout\Apm\Previous\Klarna;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\VoidRequest;

class KlarnaClient extends Client
{
    const CREDIT_SESSIONS_PATH = "credit-sessions";
    const ORDERS_PATH = "orders";
    const CAPTURES_PATH = "captures";
    const VOIDS_PATH = "voids";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param CreditSessionRequest $creditSessionRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createCreditSession(CreditSessionRequest $creditSessionRequest)
    {
        return $this->apiClient->post(
            $this->buildPath($this->getBaseUrl(), self::CREDIT_SESSIONS_PATH),
            $creditSessionRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $sessionId
     * @return array
     * @throws CheckoutApiException
     */
    public function getCreditSession($sessionId)
    {
        return $this->apiClient->get($this->buildPath(
            $this->getBaseUrl(),
            self::CREDIT_SESSIONS_PATH,
            $sessionId
        ), $this->sdkAuthorization());
    }

    /**
     * @param $paymentId
     * @param OrderCaptureRequest $orderCaptureRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function capturePayment($paymentId, OrderCaptureRequest $orderCaptureRequest)
    {
        return $this->apiClient->post(
            $this->buildPath($this->getBaseUrl(), self::ORDERS_PATH, $paymentId, self::CAPTURES_PATH),
            $orderCaptureRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $paymentId
     * @param VoidRequest $voidRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function voidPayment($paymentId, VoidRequest $voidRequest)
    {
        return $this->apiClient->post(
            $this->buildPath($this->getBaseUrl(), self::ORDERS_PATH, $paymentId, self::VOIDS_PATH),
            $voidRequest,
            $this->sdkAuthorization()
        );
    }


    private function getBaseUrl()
    {
        return $this->configuration->getEnvironment()->isSandbox() ? "klarna-external" : "klarna";
    }
}
