<?php

namespace Checkout\Payments\Four;

class CaptureRequest
{
    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of CaptureType
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
