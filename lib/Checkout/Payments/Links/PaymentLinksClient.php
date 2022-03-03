<?php

namespace Checkout\Payments\Links;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class PaymentLinksClient extends Client
{

    private const PAYMENT_LINKS = "payment-links";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getPaymentLink(string $id)
    {
        return $this->apiClient->get($this->buildPath(self::PAYMENT_LINKS, $id), $this->sdkAuthorization());
    }

    /**
     * @param PaymentLinkRequest $paymentLinkRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createPaymentLink(PaymentLinkRequest $paymentLinkRequest)
    {
        return $this->apiClient->post(self::PAYMENT_LINKS, $paymentLinkRequest, $this->sdkAuthorization());
    }

}
