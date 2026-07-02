<?php

namespace Checkout\Payments\Setups\Common\BillingDescriptor;

class PaymentSetupBillingDescriptor
{
    /**
     * A dynamic description of the payment.
     * [Optional]
     * <= 25 characters
     * @var string
     */
    public $name;

    /**
     * The city from which the payment was made.
     * [Optional]
     * <= 13 characters
     * @var string
     */
    public $city;

    /**
     * The reference shown on the statement.
     * [Optional]
     * <= 50 characters
     * @var string
     */
    public $reference;
}
