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
    public function retrieveAllEventTypes($version = null)
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
    public function retrieveEvents(RetrieveEventsRequest $eventsRequest = null)
    {
        return $this->apiClient->query(self::EVENTS_PATH, $eventsRequest, $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEvent($eventId)
    {
        return $this->apiClient->get($this->buildPath(self::EVENTS_PATH, $eventId), $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @param $notificationId
     * @return array
     * @throws CheckoutApiException
     */
    public function retrieveEventNotification($eventId, $notificationId)
    {
        return $this->apiClient->get(
            $this->buildPath(self::EVENTS_PATH, $eventId, $notificationId),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $eventId
     * @param $webhookId
     * @return array
     * @throws CheckoutApiException
     */
    public function retryWebhook($eventId, $webhookId)
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
