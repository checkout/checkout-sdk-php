<?php

namespace Checkout\Payments\Previous;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\PaymentsQueryFilter;
use Checkout\Payments\RefundRequest;
use Checkout\Payments\VoidRequest;

class PaymentsClient extends Client
{
    const PAYMENTS_PATH = "payments";

    public function __construct(
        ApiClient $apiClient,
        CheckoutConfiguration $configuration
    ) {
        parent::__construct(
            $apiClient,
            $configuration,
            AuthorizationType::$secretKey
        );
    }

    /**
     * @param PaymentRequest $paymentRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function requestPayment(
        PaymentRequest $paymentRequest,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            self::PAYMENTS_PATH,
            $paymentRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param PayoutRequest $payoutRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function requestPayout(
        PayoutRequest $payoutRequest,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            self::PAYMENTS_PATH,
            $payoutRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param PaymentsQueryFilter $queryFilter
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getPaymentsList(
        PaymentsQueryFilter $queryFilter
    ): array {
        return $this->apiClient->query(
            self::PAYMENTS_PATH,
            $queryFilter,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $paymentId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getPaymentDetails(string $paymentId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::PAYMENTS_PATH, $this->encodePathSegment($paymentId)),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $paymentId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getPaymentActions(string $paymentId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::PAYMENTS_PATH, $this->encodePathSegment($paymentId), "actions"),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $paymentId
     * @param CaptureRequest|null $captureRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function capturePayment(
        string $paymentId,
        ?CaptureRequest $captureRequest = null,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::PAYMENTS_PATH, $this->encodePathSegment($paymentId), "captures"),
            $captureRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param string $paymentId
     * @param RefundRequest|null $refundRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function refundPayment(
        string $paymentId,
        ?RefundRequest $refundRequest = null,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::PAYMENTS_PATH, $this->encodePathSegment($paymentId), "refunds"),
            $refundRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param string $paymentId
     * @param VoidRequest|null $voidRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function voidPayment(
        string $paymentId,
        ?VoidRequest $voidRequest = null,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::PAYMENTS_PATH, $this->encodePathSegment($paymentId), "voids"),
            $voidRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }
}
