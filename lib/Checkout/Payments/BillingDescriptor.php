<?php

namespace Checkout\Payments;

class BillingDescriptor
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $city;

    //Only available in four

    /**
     * @var string
     */
    public $reference;
}
