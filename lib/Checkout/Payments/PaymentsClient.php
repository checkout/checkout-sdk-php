<?php

namespace Checkout\Payments;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class PaymentsClient extends Client
{

    private const PAYMENTS_PATH = "payments";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param PaymentRequest $paymentRequest
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestPayment(PaymentRequest $paymentRequest, string $idempotencyKey = null)
    {
        return $this->apiClient->post(self::PAYMENTS_PATH, $paymentRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * @param PayoutRequest $payoutRequest
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function requestPayout(PayoutRequest $payoutRequest, string $idempotencyKey = null)
    {
        return $this->apiClient->post(self::PAYMENTS_PATH, $payoutRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * @param string $paymentId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getPaymentDetails(string $paymentId)
    {
        return $this->apiClient->get($this->buildPath(self::PAYMENTS_PATH, $paymentId), $this->sdkAuthorization());
    }

    /**
     * @param string $paymentId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getPaymentActions(string $paymentId)
    {
        return $this->apiClient->get($this->buildPath(self::PAYMENTS_PATH, $paymentId, "actions"), $this->sdkAuthorization());
    }

    /**
     * @param string $paymentId
     * @param CaptureRequest|null $captureRequest
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function capturePayment(string $paymentId, CaptureRequest $captureRequest = null, string $idempotencyKey = null)
    {
        return $this->apiClient->post($this->buildPath(self::PAYMENTS_PATH, $paymentId, "captures"), $captureRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * @param string $paymentId
     * @param RefundRequest|null $refundRequest
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function refundPayment(string $paymentId, RefundRequest $refundRequest = null, string $idempotencyKey = null)
    {
        return $this->apiClient->post($this->buildPath(self::PAYMENTS_PATH, $paymentId, "refunds"), $refundRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * @param string $paymentId
     * @param VoidRequest|null $voidRequest
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function voidPayment(string $paymentId, VoidRequest $voidRequest = null, string $idempotencyKey = null)
    {
        return $this->apiClient->post($this->buildPath(self::PAYMENTS_PATH, $paymentId, "voids"), $voidRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

}
