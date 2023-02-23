<?php

namespace Checkout\Tests\Webhooks\Previous;

use Checkout\CheckoutApiException;
use Checkout\CheckoutArgumentException;
use Checkout\CheckoutAuthorizationException;
use Checkout\CheckoutException;
use Checkout\PlatformType;
use Checkout\Tests\SandboxTestFixture;
use Checkout\Webhooks\Previous\WebhookRequest;

class WebhooksIntegrationTest extends SandboxTestFixture
{

    const EVENT_TYPES = array("invoice.cancelled", "card.updated");

    /**
     * @before
     * @throws CheckoutAuthorizationException
     * @throws CheckoutArgumentException
     * @throws CheckoutException
     */
    public function cleanUp()
    {
        $this->init(PlatformType::$previous);
        $response = $this->previousApi->getWebhooksClient()->retrieveWebhooks();
        if (array_key_exists("items", $response)) {
            foreach ($response["items"] as $webhook) {
                $this->assertResponse($webhook, "id");
                $this->previousApi->getWebhooksClient()->removeWebhook($webhook["id"]);
            }
        }
    }

    /**
     * @test
     * @throws CheckoutApiException
     */
    public function shouldCreateUpdateAndDeleteWebhook()
    {
        $this->markTestSkipped("unstable");
        $webhookRequest = new WebhookRequest();
        $webhookRequest->url = "https://test.checkout.com/webhooks";
        $webhookRequest->content_type = "json";
        $webhookRequest->event_types = self::EVENT_TYPES;
        $webhookRequest->active = true;

        $registerResponse = $this->previousApi->getWebhooksClient()->registerWebhook($webhookRequest);

        $this->assertNotNull($registerResponse);
        $this->assertNotNull($registerResponse["id"]);
        $this->assertEquals($webhookRequest->url, $registerResponse["url"]);
        $this->assertEquals($webhookRequest->content_type, $registerResponse["content_type"]);
        $this->assertEquals($webhookRequest->event_types, $registerResponse["event_types"]);

        // retrieve

        $webhookId = $registerResponse["id"];

        $retrieveResponse = $this->retriable(
            function () use (&$webhookId) {
                return $this->previousApi->getWebhooksClient()->retrieveWebhook($webhookId);
            }
        );

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
                return $this->previousApi->getWebhooksClient()->updateWebhook($webhookId, $updateRequest);
            }
        );

        $this->assertNotNull($updateResponse);
        $this->assertEquals($webhookId, $updateResponse["id"]);
        $this->assertEquals($updateRequest->url, $updateResponse["url"]);
        $this->assertEquals($updateRequest->content_type, $updateResponse["content_type"]);
        $this->assertEquals($updateRequest->event_types, $updateResponse["event_types"]);
        self::assertArrayHasKey("http_metadata", $updateResponse);
        self::assertEquals(200, $updateResponse["http_metadata"]->getStatusCode());

        // delete
        $deleteResponse = $this->previousApi->getWebhooksClient()->removeWebhook($webhookId);
        self::assertArrayHasKey("http_metadata", $deleteResponse);
        self::assertEquals(200, $deleteResponse["http_metadata"]->getStatusCode());
        try {
            $this->previousApi->getWebhooksClient()->retrieveWebhook($webhookId);
            $this->fail("shouldn't get here!");
        } catch (CheckoutApiException $e) {
            $this->assertEquals(self::MESSAGE_404, $e->getMessage());
        }
    }
}
