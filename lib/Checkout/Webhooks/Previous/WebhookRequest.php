<?php

namespace Checkout\Webhooks\Previous;

class WebhookRequest
{
    /**
     * @var string
     */
    public $url;

    /**
     * @var bool
     */
    public $active;

    /**
     * @var array
     */
    public $headers;

    /**
     * @var string
     */
    public $content_type;

    /**
     * @var array
     */
    public $event_types;
}
