<?php

namespace Checkout\Apm\Klarna;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\VoidRequest;

class KlarnaClient extends Client
{
    private const CREDIT_SESSIONS_PATH = "credit-sessions";
    private const ORDERS_PATH = "orders";
    private const CAPTURES_PATH = "captures";
    private const VOIDS_PATH = "voids";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param CreditSessionRequest $creditSessionRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createCreditSession(CreditSessionRequest $creditSessionRequest)
    {
        return $this->apiClient->post($this->buildPath($this->getBaseUrl(), self::CREDIT_SESSIONS_PATH),
            $creditSessionRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $sessionId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getCreditSession(string $sessionId)
    {
        return $this->apiClient->get($this->buildPath($this->getBaseUrl(), self::CREDIT_SESSIONS_PATH, $sessionId), $this->sdkAuthorization());
    }

    /**
     * @param string $paymentId
     * @param OrderCaptureRequest $orderCaptureRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function capturePayment(string $paymentId, OrderCaptureRequest $orderCaptureRequest)
    {
        return $this->apiClient->post($this->buildPath($this->getBaseUrl(), self::ORDERS_PATH, $paymentId, self::CAPTURES_PATH),
            $orderCaptureRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $paymentId
     * @param VoidRequest $voidRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function voidPayment(string $paymentId, VoidRequest $voidRequest)
    {
        return $this->apiClient->post($this->buildPath($this->getBaseUrl(), self::ORDERS_PATH, $paymentId, self::VOIDS_PATH),
            $voidRequest, $this->sdkAuthorization());
    }


    private function getBaseUrl(): string
    {
        return $this->configuration->getEnvironment()->isSandbox() ? "klarna-external" : "klarna";
    }
}
