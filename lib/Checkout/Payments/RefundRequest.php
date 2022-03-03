<?php

namespace Checkout\Payments;

class RefundRequest
{
    public int $amount;

    public string $reference;

    public array $metadata;

}
