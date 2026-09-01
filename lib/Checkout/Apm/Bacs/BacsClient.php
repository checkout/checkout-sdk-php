<?php

namespace Checkout\Apm\Bacs;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

/**
 * Bacs Direct Debit client.
 */
class BacsClient extends Client
{
    const APMS_PATH = "apms";
    const BACS_PATH = "bacs";
    const NOTIFICATIONS_PATH = "notifications";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * Sends a Bacs Direct Debit pre-notification (advance notice) to a payer ahead of collecting
     * funds from their account.
     *
     * @param BacsNotificationRequest $bacsNotificationRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function sendNotification(BacsNotificationRequest $bacsNotificationRequest)
    {
        return $this->apiClient->post(
            $this->buildPath(self::APMS_PATH, self::BACS_PATH, self::NOTIFICATIONS_PATH),
            $bacsNotificationRequest,
            $this->sdkAuthorization()
        );
    }
}
