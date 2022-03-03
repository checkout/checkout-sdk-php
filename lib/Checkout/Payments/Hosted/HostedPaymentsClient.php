<?php

namespace Checkout\Payments\Hosted;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class HostedPaymentsClient extends Client
{

    private const HOSTED_PAYMENTS = "hosted-payments";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws CheckoutApiException
     */
    public function getHostedPaymentsPageDetails(string $id)
    {
        return $this->apiClient->get($this->buildPath(self::HOSTED_PAYMENTS, $id), $this->sdkAuthorization());
    }

    /**
     * @param HostedPaymentsSessionRequest $hostedPaymentRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function createHostedPaymentsPageSession(HostedPaymentsSessionRequest $hostedPaymentRequest)
    {
        return $this->apiClient->post(self::HOSTED_PAYMENTS, $hostedPaymentRequest, $this->sdkAuthorization());
    }

}
