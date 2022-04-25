<?php

namespace Checkout\Payments;

class CaptureRequest
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
