<?php

namespace Checkout\Tests\Events;

use Checkout\CheckoutApiException;
use Checkout\Events\EventsClient;
use Checkout\Events\RetrieveEventsRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class EventsClientTest extends UnitTestFixture
{
    private EventsClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new EventsClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveAllEventTypes(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->retrieveAllEventTypes();
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveEvents(): void
    {
        $this->apiClient->method("query")
            ->willReturn("foo");

        $response = $this->client->retrieveEvents(new RetrieveEventsRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveEvent(): void
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->retrieveEvent("event_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveEventNotification(): void
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->retrieveEventNotification("event_id", "notification_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetryWebhook(): void
    {
        $this->apiClient->method("post")
            ->willReturn("foo");


        $response = $this->client->retryWebhook("event_id", "webhook_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetryAllWebhooks(): void
    {
        $this->apiClient->method("post")
            ->willReturn("foo");


        $response = $this->client->retryAllWebhooks("event_id");
        $this->assertNotNull($response);
    }

}
