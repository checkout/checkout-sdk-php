<?php

namespace Checkout\Webhooks;

use Checkout\ApiClient;
use Checkout\AuthorizationType;
use Checkout\CheckoutApiException;
use Checkout\CheckoutConfiguration;
use Checkout\Client;

class WebhooksClient extends Client
{
    private const WEBHOOKS_PATH = "webhooks";

    public function __construct(ApiClient $apiClient, CheckoutConfiguration $configuration)
    {
        parent::__construct($apiClient, $configuration, AuthorizationType::$secretKey);
    }

    /**
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveWebhooks()
    {
        return $this->apiClient->get(self::WEBHOOKS_PATH, $this->sdkAuthorization());
    }

    /**
     * @param string $webhookId
     * @return mixed
     * @throws CheckoutApiException
     */
    public function retrieveWebhook(string $webhookId)
    {
        return $this->apiClient->get($this->buildPath(self::WEBHOOKS_PATH, $webhookId), $this->sdkAuthorization());
    }

    /**
     * @param WebhookRequest $webhookRequest
     * @param string|null $idempotencyKey
     * @return mixed
     * @throws CheckoutApiException
     */
    public function registerWebhook(WebhookRequest $webhookRequest, string $idempotencyKey = null)
    {
        return $this->apiClient->post(self::WEBHOOKS_PATH, $webhookRequest, $this->sdkAuthorization(), $idempotencyKey);
    }

    /**
     * @param string $webhookId
     * @param WebhookRequest $webhookRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function updateWebhook(string $webhookId, WebhookRequest $webhookRequest)
    {
        return $this->apiClient->put($this->buildPath(self::WEBHOOKS_PATH, $webhookId), $webhookRequest, $this->sdkAuthorization());
    }

    /**
     * TODO Review
     * @param string $webhookId
     * @param WebhookRequest $webhookRequest
     * @return mixed
     * @throws CheckoutApiException
     */
    public function patchWebhook(string $webhookId, WebhookRequest $webhookRequest)
    {
        return $this->apiClient->patch($this->buildPath(self::WEBHOOKS_PATH, $webhookId), $webhookRequest, $this->sdkAuthorization());
    }

    /**
     * @param string $webhookId
     * @throws CheckoutApiException
     */
    public function removeWebhook(string $webhookId): void
    {
        $this->apiClient->delete($this->buildPath(self::WEBHOOKS_PATH, $webhookId), $this->sdkAuthorization());
    }

}
