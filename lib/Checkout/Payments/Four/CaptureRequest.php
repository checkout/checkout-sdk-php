<?php

namespace Checkout\Payments\Four;

class CaptureRequest
{
    public $amount;

    //CaptureType
    public $capture_type;

    public $reference;

    // array
    public $metadata;

}
