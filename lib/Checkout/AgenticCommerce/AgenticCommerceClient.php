<?php

namespace Checkout\AgenticCommerce;

use Checkout\ApiClient;
use Checkout\AgenticCommerce\Entities\DelegatedPaymentHeaders;
use Checkout\AgenticCommerce\Requests\DelegatedPaymentRequest;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class AgenticCommerceClient extends Client
{
    const AGENTIC_COMMERCE_PATH = "agentic_commerce";
    const DELEGATE_PAYMENT_PATH = "delegate_payment";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * [Beta]
     * Creates a delegated payment token to securely enable Checkout.com merchants to process agentic payments.
     * Requires Signature and Timestamp headers (see DelegatedPaymentHeaders); optional Cko-Idempotency-Key and API-Version.
     *
     * @param DelegatedPaymentRequest $request
     * @param DelegatedPaymentHeaders $integrityHeaders
     * @param string|null $idempotencyKey
     * @return array
     * @throws CheckoutApiException
     */
    public function createDelegatedPaymentToken(
        DelegatedPaymentRequest $request,
        DelegatedPaymentHeaders $integrityHeaders,
        ?string $idempotencyKey = null
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::AGENTIC_COMMERCE_PATH, self::DELEGATE_PAYMENT_PATH),
            $request,
            $this->sdkAuthorization(),
            $idempotencyKey,
            $integrityHeaders
        );
    }
}
