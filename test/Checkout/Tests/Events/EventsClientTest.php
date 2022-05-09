<?php

namespace Checkout\Tests\Events;

use Checkout\Events\EventsClient;
use Checkout\Events\RetrieveEventsRequest;
use Checkout\PlatformType;
use Checkout\Tests\UnitTestFixture;

class EventsClientTest extends UnitTestFixture
{
    /**
     * @var EventsClient
     */
    private $client;

    /**
     * @before
     */
    public function init()
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new EventsClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     */
    public function shouldRetrieveAllEventTypes()
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->retrieveAllEventTypes();
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldRetrieveEvents()
    {
        $this->apiClient->method("query")
            ->willReturn("foo");

        $response = $this->client->retrieveEvents(new RetrieveEventsRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldRetrieveEvent()
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->retrieveEvent("event_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldRetrieveEventNotification()
    {
        $this->apiClient->method("get")
            ->willReturn("foo");


        $response = $this->client->retrieveEventNotification("event_id", "notification_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldRetryWebhook()
    {
        $this->apiClient->method("post")
            ->willReturn("foo");


        $response = $this->client->retryWebhook("event_id", "webhook_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     */
    public function shouldRetryAllWebhooks()
    {
        $this->apiClient->method("post")
            ->willReturn("foo");


        $response = $this->client->retryAllWebhooks("event_id");
        $this->assertNotNull($response);
    }

}
