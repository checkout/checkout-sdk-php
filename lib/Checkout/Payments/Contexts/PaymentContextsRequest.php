<?php

namespace Checkout\Payments\Contexts;

use Checkout\Common\CustomerRequest;
use Checkout\Payments\Request\Source\AbstractRequestSource;
use Checkout\Payments\ShippingDetails;

class PaymentContextsRequest
{
    /**
     * @var AbstractRequestSource
     */
    public $source;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string value of PaymentType
     */
    public $payment_type;

    /**
     * @var string
     */
    public $authorizationType;

    /**
     * @var bool
     */
    public $capture;

    /**
     * @var CustomerRequest
     */
    public $customer;

    /**
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * @var PaymentContextsProcessing
     */
    public $processing;

    /**
     * @var string
     */
    public $processing_channel_id;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $success_url;

    /**
     * @var string
     */
    public $failure_url;

    /**
     * @var array of Checkout\Payments\Contexts\PaymentContextsItems
     */
    public $items;
}
