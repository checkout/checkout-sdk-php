<?php

namespace Checkout\Payments;

class ReversePaymentRequest
{
    /**
     * @var string
     */
    public $reference;

    /**
     * @var object
     */
    public $metadata;
}
