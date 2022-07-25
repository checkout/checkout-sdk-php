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

    //Not available on previous

    /**
     * @var string
     */
    public $reference;
}
