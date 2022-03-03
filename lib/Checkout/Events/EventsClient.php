<?php

namespace Checkout\Events;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class EventsClient extends Client
{
    private const EVENTS_PATH = "events";
    private const WEBHOOKS_PATH = "webhooks";
    private const RETRY_PATH = "retry";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @param string|null $version
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveAllEventTypes(string $version = null)
    {
        $path = "event-types";
        if (!empty($version)) {
            $path = $path . "?version=" . $version;
        }
        return $this->apiClient->get($path, $this->sdkAuthorization());
    }

    /**
     * @param RetrieveEventsRequest|null $eventsRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveEvents(RetrieveEventsRequest $eventsRequest = null)
    {
        return $this->apiClient->query(self::EVENTS_PATH, $eventsRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $eventId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveEvent(string $eventId)
    {
        return $this->apiClient->get($this->buildPath(self::EVENTS_PATH, $eventId), $this->sdkAuthorization());
    }

    /**
     * @param string $eventId
     * @param string $notificationId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveEventNotification(string $eventId, string $notificationId)
    {
        return $this->apiClient->get($this->buildPath(self::EVENTS_PATH, $eventId, $notificationId),
            $this->sdkAuthorization());
    }

    /**
     * @param string $eventId
     * @param string $webhookId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retryWebhook(string $eventId, string $webhookId)
    {
        return $this->apiClient->post($this->buildPath(self::EVENTS_PATH, $eventId,
            self::WEBHOOKS_PATH, $webhookId, self::RETRY_PATH), null, $this->sdkAuthorization());
    }

    /**
     * @param string $eventId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retryAllWebhooks(string $eventId)
    {
        return $this->apiClient->post($this->buildPath(self::EVENTS_PATH, $eventId,
            self::WEBHOOKS_PATH, self::RETRY_PATH), null, $this->sdkAuthorization());
    }
}
