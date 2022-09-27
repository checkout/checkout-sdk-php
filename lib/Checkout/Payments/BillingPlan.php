<?php

namespace Checkout\Payments;

class BillingPlan
{
    /**
     * @var string value of BillingPlanType
     */
    public $type;

    /**
     * @var bool
     */
    public $skip_shipping_address;

    /**
     * @var bool
     */
    public $immutable_shipping_address;
}
