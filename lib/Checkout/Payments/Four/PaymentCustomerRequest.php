<?php

namespace Checkout\Payments\Four;

use Checkout\Common\CustomerRequest;

class PaymentCustomerRequest extends CustomerRequest
{
    /**
     * @var string
     */
    public $tax_number;
}
