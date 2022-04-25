<?php

namespace Checkout\Payments\Four;

class CaptureRequest
{
    /**
     * @var int
     */
    public $amount;

    /**
     * @var CaptureType
     */
    public $capture_type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var array
     */
    public $metadata;
}
