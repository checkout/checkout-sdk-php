<?php

namespace Checkout\Tests\Webhooks;

use Checkout\CheckoutApiException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Webhooks\WebhookRequest;

class WebhooksIntegrationTest extends SandboxTestFixture
{

    const EVENT_TYPES = array("invoice.cancelled", "card.updated");

    /**
     * @before
     */
    public function cleanUp()
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
    public function shouldCreateUpdateAndDeleteWebhook()
    {

        $webhookRequest = new WebhookRequest();
        $webhookRequest->url = "https://test.checkout.com/webhooks";
        $webhookRequest->content_type = "json";
        $webhookRequest->event_types = self::EVENT_TYPES;
        $webhookRequest->active = true;

        $registerResponse = $this->defaultApi->getWebhooksClient()->registerWebhook($webhookRequest);

        $this->assertNotNull($registerResponse);
        $this->assertNotNull($registerResponse["id"]);
        $this->assertEquals($webhookRequest->url, $registerResponse["url"]);
        $this->assertEquals($webhookRequest->content_type, $registerResponse["content_type"]);
        $this->assertEquals($webhookRequest->event_types, $registerResponse["event_types"]);

        // retrieve

        $webhookId = $registerResponse["id"];

        $retrieveResponse = $this->retriable(
            function () use (&$webhookId) {
                return $this->defaultApi->getWebhooksClient()->retrieveWebhook($webhookId);
            });

        $this->assertNotNull($retrieveResponse);
        $this->assertEquals($webhookId, $retrieveResponse["id"]);
        $this->assertEquals($webhookRequest->url, $registerResponse["url"]);
        $this->assertEquals($webhookRequest->content_type, $registerResponse["content_type"]);
        $this->assertEquals($webhookRequest->event_types, $registerResponse["event_types"]);

        // update

        $updateRequest = new WebhookRequest();
        $updateRequest->url = "https://test.checkout.com/webhooks/changed";
        $updateRequest->headers = $retrieveResponse["headers"];
        $updateRequest->content_type = "json";
        $updateRequest->event_types = array("source_updated");
        $updateRequest->active = true;

        $updateResponse = $this->retriable(
            function () use (&$webhookId, &$updateRequest) {
                return $this->defaultApi->getWebhooksClient()->updateWebhook($webhookId, $updateRequest);
            });

        $this->assertNotNull($updateResponse);
        $this->assertEquals($webhookId, $updateResponse["id"]);
        $this->assertEquals($updateRequest->url, $updateResponse["url"]);
        $this->assertEquals($updateRequest->content_type, $updateResponse["content_type"]);
        $this->assertEquals($updateRequest->event_types, $updateResponse["event_types"]);

        // delete

        $this->defaultApi->getWebhooksClient()->removeWebhook($webhookId);

        try {
            $this->defaultApi->getWebhooksClient()->retrieveWebhook($webhookId);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_404, $e->getMessage());
        }

    }

}
