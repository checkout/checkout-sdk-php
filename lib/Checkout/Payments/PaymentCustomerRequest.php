<?php

namespace Checkout\Payments;

use Checkout\Common\CustomerRequest;

class PaymentCustomerRequest extends CustomerRequest
{
    /**
     * The customer's tax number.
     * @var string
     */
    public $tax_number;

    /**
     * Customer summary with registration and transaction history information.
     * @var CustomerSummary
     */
    public $summary;
}
