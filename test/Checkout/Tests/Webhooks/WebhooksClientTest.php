<?php

namespace Checkout\Tests\Webhooks;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Sources\SepaSourceRequest;
use Checkout\Sources\SourcesClient;
use Checkout\Tests\UnitTestFixture;
use Checkout\Tokens\ApplePayTokenRequest;
use Checkout\Tokens\CardTokenRequest;
use Checkout\Tokens\GooglePayTokenRequest;
use Checkout\Tokens\TokensClient;
use Checkout\Webhooks\WebhookRequest;
use Checkout\Webhooks\WebhooksClient;

class WebhooksClientTest extends UnitTestFixture
{
    private WebhooksClient $client;

    /**
     * @before
     */
    public function init(): void
    {
        $this->initMocks(PlatformType::$default);
        $this->client = new WebhooksClient($this->apiClient, $this->configuration);
    }


    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveWebhooks(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->retrieveWebhooks();
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRetrieveWebhook(): void
    {
        $this->apiClient
            ->method("get")
            ->willReturn("foo");

        $response = $this->client->retrieveWebhook("webhook_id");
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldRegisterWebhook(): void
    {
        $this->apiClient
            ->method("post")
            ->willReturn("foo");

        $response = $this->client->registerWebhook(new WebhookRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldUpdateWebhook(): void
    {
        $this->apiClient
            ->method("put")
            ->willReturn("foo");

        $response = $this->client->updateWebhook("webhook_id", new WebhookRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldPatchWebhook(): void
    {
        $this->apiClient
            ->method("patch")
            ->willReturn("foo");

        $response = $this->client->patchWebhook("webhook_id", new WebhookRequest());
        $this->assertNotNull($response);
    }

    /**
     * @test
     * @doesNotPerformAssertions
     * @throws CheckoutApiException
     */
    public function shouldRemoveWebhook(): void
    {
        $this->apiClient->method("delete");

        $this->client->removeWebhook("webhook_id");
    }

}
