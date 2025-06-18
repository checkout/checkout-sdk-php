<?php

namespace Checkout\Events\Previous;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class EventsClient extends Client
{
    const EVENTS_PATH = "events";
    const WEBHOOKS_PATH = "webhooks";
    const RETRY_PATH = "retry";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param string|null $version
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveAllEventTypes(?string $version = null): array
    {
        $path = "event-types";
        if (!empty($version)) {
            $path = $path . "?version=" . $version;
        }
        return $this->apiClient->get($path, $this->sdkAuthorization());
    }

    /**
     * @param RetrieveEventsRequest|null $eventsRequest
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEvents(?RetrieveEventsRequest $eventsRequest = null): array
    {
        return $this->apiClient->query(self::EVENTS_PATH, $eventsRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $eventId
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEvent(string $eventId): array
    {
        return $this->apiClient->get($this->buildPath(self::EVENTS_PATH, $eventId), $this->sdkAuthorization());
    }

    /**
     * @param string $eventId
     * @param string $notificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEventNotification(string $eventId, string $notificationId): array
    {
        return $this->apiClient->get(
            $this->buildPath(self::EVENTS_PATH, $eventId, $notificationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param string $eventId
     * @param string $webhookId
     * @return array
     * @throws CheckoutApiException
     */
    public function retryWebhook(string $eventId, string $webhookId): array
    {
        return $this->apiClient->post($this->buildPath(
            self::EVENTS_PATH,
            $eventId,
            self::WEBHOOKS_PATH,
            $webhookId,
            self::RETRY_PATH
        ), null, $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @return array
     * @throws CheckoutApiException
     */
    public function retryAllWebhooks($eventId)
    {
        return $this->apiClient->post($this->buildPath(
            self::EVENTS_PATH,
            $eventId,
            self::WEBHOOKS_PATH,
            self::RETRY_PATH
        ), null, $this->sdkAuthorization());
    }
}
