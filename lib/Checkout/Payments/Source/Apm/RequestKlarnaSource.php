<?php

namespace Checkout\Payments\Source\Apm;

use Checkout\Common\Address;
use Checkout\Common\Country;
use Checkout\Common\PaymentSourceType;
use Checkout\Payments\Source\AbstractRequestSource;

class RequestKlarnaSource extends AbstractRequestSource
{
    public function __construct()
    {
        parent::__construct(PaymentSourceType::$klarna);
    }

    /**
     * @var string
     */
    public $authorization_token;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var Country
     */
    public $purchase_country;

    /**
     * @var int
     */
    public $tax_amount;

    /**
     * @var Address
     */
    public $billing_address;

    /**
     * @var KlarnaCustomer
     */
    public $customer;

    /**
     * @var array of KlarnaProduct
     */
    public $products;
}
