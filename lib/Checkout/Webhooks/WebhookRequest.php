<?php

namespace Checkout\Webhooks;

class WebhookRequest
{
    public $url;

    public $active;

    public $headers;

    public $content_type;

    public $event_types;
}
