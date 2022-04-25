<?php

namespace Checkout\Payments;

class RefundRequest
{
    /**
     * @var int
     */
    public $amount;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var array
     */
    public $metadata;
}
