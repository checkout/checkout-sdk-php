<?php

namespace Checkout\Payments\Setups\Common\BillingDescriptor;

class PaymentSetupBillingDescriptor
{
    /**
     * A dynamic description of the payment.
     * [Optional]
     * max 25 characters
     * @var string
     */
    public $name;

    /**
     * The city from which the payment was made.
     * [Optional]
     * max 13 characters
     * @var string
     */
    public $city;

    /**
     * The reference shown on the statement.
     * [Optional]
     * max 50 characters
     * @var string
     */
    public $reference;
}
