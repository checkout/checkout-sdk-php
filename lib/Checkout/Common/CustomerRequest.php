<?php

namespace Checkout\Common;

use Checkout\Payments\CustomerSummary;

class CustomerRequest
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $tax_number;

    /**
     * @var Phone
     */
    public $phone;

    /**
     * @var CustomerSummary
     */
    public $summary;
}
