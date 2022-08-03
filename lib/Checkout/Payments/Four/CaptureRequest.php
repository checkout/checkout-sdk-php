<?php

namespace Checkout\Payments\Four;

use Checkout\Common\MarketplaceData;
use Checkout\Payments\BillingDescriptor;
use Checkout\Payments\ProcessingSettings;
use Checkout\Payments\ShippingDetails;

class CaptureRequest
{
    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of CaptureType
     */
    public $capture_type;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var PaymentCustomerRequest
     */
    public $customer;

    /**
     * @var string
     */
    public $description;

    /**
     * @var BillingDescriptor
     */
    public $billing_descriptor;

    /**
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * @var array of Checkout\Payments\Product
     */
    public $items;

    /**
     * @var MarketplaceData
     */
    public $marketplace;

    /**
     * @var ProcessingSettings
     */
    public $processing;

    /**
     * @var array
     */
    public $metadata;
}
