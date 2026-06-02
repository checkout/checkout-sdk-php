<?php

namespace Checkout\Payments\Request;

class PaymentRetryRequest
{
    /**
     * Indicates whether asynchronous retries are enabled for the payment.
     * [Required]
     * @var bool $enabled
     */
    public $enabled;

    /**
     * Configuration of asynchronous Dunning retries.
     * [Optional]
     * @var DunningRetryRequest|null $dunning
     */
    public $dunning;

    /**
     * Configuration of asynchronous Downtime retries.
     * [Optional]
     * @var DowntimeRetryRequest|null $downtime
     */
    public $downtime;
}
