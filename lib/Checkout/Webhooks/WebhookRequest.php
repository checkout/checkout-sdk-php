<?php

namespace Checkout\Webhooks;

class WebhookRequest
{
    public string $url;

    public bool $active;

    public array $headers;

    public string $content_type;

    public array $event_types;
}
