<?php

namespace Checkout\Payments\Request;

class PaymentRetryRequest
{
    /**
     * @var bool
     */
    public $enabled;

    /**
     * @var int
     */
    public $max_attempts;

    /**
     * @var int
     */
    public $end_after_days;

}
