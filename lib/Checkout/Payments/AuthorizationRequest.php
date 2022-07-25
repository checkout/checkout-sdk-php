<?php

namespace Checkout\Payments;

class AuthorizationRequest
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
