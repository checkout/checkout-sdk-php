<?php

namespace Checkout\Payments;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\Request\PaymentRequest;
use Checkout\Payments\Request\PayoutRequest;
use Checkout\Payments\Request\UnreferencedRefundRequest;

class PaymentsClient extends Client
{
    const PAYMENTS_PATH = "payments";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param PaymentRequest $paymentRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function requestPayment(PaymentRequest $paymentRequest, ?string $idempotencyKey = null): array
    {
        return $this->apiClient->post(self::PAYMENTS_PATH, $paymentRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * @param PayoutRequest $payoutRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function requestPayout(PayoutRequest $payoutRequest, ?string $idempotencyKey = null): array
    {
        return $this->apiClient->post(self::PAYMENTS_PATH, $payoutRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * Requests an unreferenced refund, sent to POST /payments.
     *
     * @param UnreferencedRefundRequest $unreferencedRefundRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function requestUnreferencedRefund(
        UnreferencedRefundRequest $unreferencedRefundRequest,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            self::PAYMENTS_PATH,
            $unreferencedRefundRequest,
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
    public function getPaymentsList(PaymentsQueryFilter $queryFilter): array
    {
        return $this->apiClient->query(self::PAYMENTS_PATH, $queryFilter, $this->sdkAuthorization());
    }

    /**
     * @param string $paymentId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function getPaymentDetails(string $paymentId): array
    {
        return $this->apiClient->get($this->buildPath(
            self::PAYMENTS_PATH,
            $this->encodePathSegment($paymentId)
        ), $this->sdkAuthorization());
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
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "actions"
            ),
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
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "captures"
            ),
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
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "refunds"
            ),
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
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "voids"
            ),
            $voidRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param string $paymentId
     * @param AuthorizationRequest|null $authorizationRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function incrementPaymentAuthorization(
        string $paymentId,
        ?AuthorizationRequest $authorizationRequest = null,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "authorizations"
            ),
            $authorizationRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param string $paymentId - The unique payment identifier. (Required)
     * @param CancelScheduledRetryRequest|null $cancellationRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function cancelAScheduledRetry(
        string $paymentId,
        ?CancelScheduledRetryRequest $cancelScheduledRetryRequest = null,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "cancellations"
            ),
            $cancelScheduledRetryRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param string $paymentId - The unique payment identifier. (Required)
     * @param ReversePaymentRequest|null $reversePaymentRequest
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function reversePayment(
        string $paymentId,
        ?ReversePaymentRequest $reversePaymentRequest = null,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(
                self::PAYMENTS_PATH,
                $this->encodePathSegment($paymentId),
                "reversals"
            ),
            $reversePaymentRequest,
            $this->sdkAuthorization(),
            $idempotencyKey
        );
    }

    /**
     * @param SearchPaymentRequest $searchPaymentRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function searchPayments(SearchPaymentRequest $searchPaymentRequest): array
    {
        return $this->apiClient->post(
            $this->buildPath(
                self::PAYMENTS_PATH,
                "search"
            ),
            $searchPaymentRequest,
            $this->sdkAuthorization()
        );
    }

}
