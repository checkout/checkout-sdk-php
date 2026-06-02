<?php

namespace Checkout\Payments\Request;

class DowntimeRetryRequest
{
    /**
     * Indicates if Checkout.com retries the payment when it's declined due to issuer or acquirer downtime.
     * [Required]
     * @var bool $enabled
     */
    public $enabled;
}
