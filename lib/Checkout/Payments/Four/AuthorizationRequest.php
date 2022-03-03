<?php

namespace Checkout\Payments\Four;

class AuthorizationRequest
{
    public int $amount;

    public string $reference;

    public array $metadata;
}
