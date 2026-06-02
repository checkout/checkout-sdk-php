<?php

namespace Checkout\Payments\Request;

class DunningRetryRequest
{
    /**
     * Indicates if Checkout.com retries the payment when it's declined with a supported decline code.
     * [Required]
     * @var bool $enabled
     */
    public $enabled;

    /**
     * The maximum number of authorization retry attempts, excluding the initial authorization.
     * [Optional]
     * default 6
     * min 1
     * max 15
     * @var int|null $max_attempts
     */
    public $max_attempts;

    /**
     * The maximum number of days between the initial request and the final retry attempt.
     * [Optional]
     * default 30
     * min 1
     * max 60
     * @var int|null $end_after_days
     */
    public $end_after_days;
}
