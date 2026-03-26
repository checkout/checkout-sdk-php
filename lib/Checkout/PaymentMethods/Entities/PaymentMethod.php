<?php

namespace Checkout\PaymentMethods\Entities;

class PaymentMethod
{
    /**
     * The type of the payment method. (Required)
     *
     * @var string
     */
    public $type;

    /**
     * The unique identifier for the merchant, provided by the partner.
     *
     * @var string
     */
    public $partner_merchant_id;
}
