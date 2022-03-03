<?php

namespace Checkout\Payments\Four;

class CaptureRequest
{
    public int $amount;

    //CaptureType
    public string $capture_type;

    public string $reference;

    public array $metadata;

}
