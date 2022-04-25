<?php

namespace Checkout\Payments;

class VoidRequest
{
    /**
     * @var string
     */
    public $reference;

    /**
     * @var array
     */
    public $metadata;
}
