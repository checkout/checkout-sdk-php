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
     * @var bool
     */
    public $auto_capture;

    /**
     * @var array
     */
    public $billing_address;

    /**
     * @var array
     */
    public $shipping_address;

    /**
     * @var int
     */
    public $tax_amount;

    /**
     * @var array
     */
    public $products;

    /**
     * @var array
     */
    public $customer;

    /**
     * @var string
     */
    public $merchant_reference1;

    /**
     * @var string
     */
    public $merchant_reference2;

    /**
     * @var string
     */
    public $merchant_data;

    /**
     * @var array
     */
    public $attachment;
}
