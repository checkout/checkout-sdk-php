<?php

namespace Checkout\Payments\Request;

/**
 * Configuration of asynchronous retries.
 *
 * For more information about asynchronous retries and supported response codes, see Configure scheduled retries
 * (https://www.checkout.com/docs/payments/manage-payments/configure-scheduled-retries).
 *
 * Used by the retry field of a payment request (POST /payments). Not to be confused with CustomerRetry, which
 * configures customer retries on hosted payments, payment links and payment sessions.
 */
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
