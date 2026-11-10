<?php

namespace Checkout\Events\Previous;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutConfiguration;
use Checkout\CheckoutUtils;
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
     * @throws CheckoutArgumentException
     */
    public function retrieveAllEventTypes($version = null)
    {
        $path = "event-types";
        if ($version !== null && $version !== "") {
            $path = "event-types?" . CheckoutUtils::buildQuery(["version" => $version]);
        }
        return $this->apiClient->get($path, $this->sdkAuthorization());
    }

    /**
     * @param RetrieveEventsRequest|null $eventsRequest
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retrieveEvents(RetrieveEventsRequest $eventsRequest = null)
    {
        return $this->apiClient->query(self::EVENTS_PATH, $eventsRequest, $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retrieveEvent($eventId)
    {
        return $this->apiClient->get($this->buildPath(
            self::EVENTS_PATH,
            $this->encodePathSegment($eventId)
        ), $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @param $notificationId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retrieveEventNotification($eventId, $notificationId)
    {
        return $this->apiClient->get(
            $this->buildPath(
                self::EVENTS_PATH,
                $this->encodePathSegment($eventId),
                $this->encodePathSegment($notificationId)
            ),
            $this->sdkAuthorization()
        );
    }

    /**
     * @param $eventId
     * @param $webhookId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retryWebhook($eventId, $webhookId)
    {
        return $this->apiClient->post($this->buildPath(
            self::EVENTS_PATH,
            $this->encodePathSegment($eventId),
            self::WEBHOOKS_PATH,
            $this->encodePathSegment($webhookId),
            self::RETRY_PATH
        ), null, $this->sdkAuthorization());
    }

    /**
     * @param $eventId
     * @return array
     * @throws CheckoutApiException
     * @throws CheckoutArgumentException
     */
    public function retryAllWebhooks($eventId)
    {
        return $this->apiClient->post($this->buildPath(
            self::EVENTS_PATH,
            $this->encodePathSegment($eventId),
            self::WEBHOOKS_PATH,
            self::RETRY_PATH
        ), null, $this->sdkAuthorization());
    }
}
