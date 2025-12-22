<?php

namespace Checkout\Payments\Setups;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;
use Checkout\Payments\Setups\Request\PaymentSetupRequest;

class PaymentSetupsClient extends Client
{

    const PAYMENT_SETUPS_PATH = "payments/setups";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKeyOrOAuth);
    }

    /**
     * @param PaymentSetupRequest $paymentSetupRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function createPaymentSetup(
        PaymentSetupRequest $paymentSetupRequest
    ): array {
        return $this->apiClient->post(
            self::PAYMENT_SETUPS_PATH,
            $paymentSetupRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $paymentSetupId
     * @param PaymentSetupRequest $paymentSetupRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function updatePaymentSetup(
        string $paymentSetupId,
        PaymentSetupRequest $paymentSetupRequest
    ): array {
        return $this->apiClient->put(
            $this->buildPath(self::PAYMENT_SETUPS_PATH, $paymentSetupId),
            $paymentSetupRequest,
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $paymentSetupId
     * @return array
     * @throws CheckoutApiException
     */
    public function getPaymentSetup(string $paymentSetupId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::PAYMENT_SETUPS_PATH, $paymentSetupId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $paymentSetupId
     * @param string $paymentMethodOptionId
     * @return array
     * @throws CheckoutApiException
     */
    public function confirmPaymentSetup(
        string $paymentSetupId,
        string $paymentMethodOptionId
    ): array {
        return $this->apiClient->post(
            $this->buildPath(self::PAYMENT_SETUPS_PATH, $paymentSetupId, "confirm", $paymentMethodOptionId),
            null,
            $this->sdkAuthorization()
        );
    }
}
