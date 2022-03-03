<?php

namespace Checkout\Tests\Webhooks;

use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Webhooks\WebhookRequest;
use Exception;

class WebhooksIntegrationTest extends SandboxTestFixture
{

    private const EVENT_TYPES = array("invoice.cancelled", "card.updated");

    /**
     * @before
     */
    public function cleanUp(): void
    {
        $this->init(PlatformType::$default);
        $webhooks = $this->defaultApi->getWebhooksClient()->retrieveWebhooks();
        if (!$webhooks) {
            return;
        }
        foreach ($webhooks as $webhook) {
            $this->assertResponse($webhook, "id");
            $this->defaultApi->getWebhooksClient()->removeWebhook($webhook["id"]);
        }
    }

    /**
     * @test
     */
    public function shouldCreateUpdateAndDeleteWebhook(): void
    {

        $webhookRequest = new WebhookRequest();
        $webhookRequest->url = "https://test.checkout.com/webhooks";
        $webhookRequest->content_type = "json";
        $webhookRequest->event_types = self::EVENT_TYPES;
        $webhookRequest->active = true;

        $registerResponse = $this->defaultApi->getWebhooksClient()->registerWebhook($webhookRequest);

        self::assertNotNull($registerResponse);
        self::assertNotNull($registerResponse["id"]);
        self::assertEquals($webhookRequest->url, $registerResponse["url"]);
        self::assertEquals($webhookRequest->content_type, $registerResponse["content_type"]);
        self::assertEquals($webhookRequest->event_types, $registerResponse["event_types"]);

        // retrieve

        $webhookId = $registerResponse["id"];

        $retrieveResponse = self::retriable(fn() => $this->defaultApi->getWebhooksClient()->retrieveWebhook($webhookId));

        self::assertNotNull($retrieveResponse);
        self::assertEquals($webhookId, $retrieveResponse["id"]);
        self::assertEquals($webhookRequest->url, $registerResponse["url"]);
        self::assertEquals($webhookRequest->content_type, $registerResponse["content_type"]);
        self::assertEquals($webhookRequest->event_types, $registerResponse["event_types"]);

        // update

        $updateRequest = new WebhookRequest();
        $updateRequest->url = "https://test.checkout.com/webhooks/changed";
        $updateRequest->headers = $retrieveResponse["headers"];
        $updateRequest->content_type = "json";
        $updateRequest->event_types = array("source_updated");
        $updateRequest->active = true;

        $updateResponse = self::retriable(fn() => $this->defaultApi->getWebhooksClient()->updateWebhook($webhookId, $updateRequest));

        self::assertNotNull($updateResponse);
        self::assertEquals($webhookId, $updateResponse["id"]);
        self::assertEquals($updateRequest->url, $updateResponse["url"]);
        self::assertEquals($updateRequest->content_type, $updateResponse["content_type"]);
        self::assertEquals($updateRequest->event_types, $updateResponse["event_types"]);

        // delete

        $this->defaultApi->getWebhooksClient()->removeWebhook($webhookId);

        try {
            $this->defaultApi->getWebhooksClient()->retrieveWebhook($webhookId);
            self::fail("shouldn't get here!");
        } catch (Exception $e) {
            self::assertEquals(self::MESSAGE_404, $e->getMessage());
        }

    }

}
