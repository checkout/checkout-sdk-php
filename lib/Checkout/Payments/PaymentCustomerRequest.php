<?php

namespace Checkout\Payments;

use Checkout\Common\CustomerRequest;

class PaymentCustomerRequest extends CustomerRequest
{
    /**
     * @var string
     */
    public $tax_number;
}
