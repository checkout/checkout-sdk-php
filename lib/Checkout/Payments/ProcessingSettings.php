<?php

namespace Checkout\Payments;

class ProcessingSettings
{
    /**
     * @var string
     */
    public $order_id;

    /**
     * @var int
     */
    public $tax_amount;

    /**
     * @var int
     */
    public $discount_amount;

    /**
     * @var int
     */
    public $duty_amount;

    /**
     * @var int
     */
    public $shipping_amount;

    /**
     * @var int
     */
    public $shipping_tax_amount;

    /**
     * @var bool
     */
    public $aft;

    /**
     * @var string value of PreferredSchema
     */
    public $preferred_scheme;

    /**
     * @var string value of MerchantInitiatedReason
     */
    public $merchant_initiated_reason;

    /**
     * @var int
     */
    public $campaign_id;

    /**
     * @var string value of ProductType
     */
    public $product_type;

    /**
     * @var string
     */
    public $open_id;

    /**
     * @var int
     */
    public $original_order_amount;

    /**
     * @var string
     */
    public $receipt_id;

    /**
     * @var string value of TerminalType
     */
    public $terminal_type;

    /**
     * @var string value of OsType
     */
    public $os_type;

    /**
     * @var string
     */
    public $invoice_id;

    /**
     * @var string
     */
    public $brand_name;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var string value of ShippingPreference
     */
    public $shipping_preference;

    /**
     * @var string value of UserAction
     */
    public $user_action;

    /**
     * @var array
     */
    public $set_transaction_context;

    /**
     * @var array of AirlineData
     */
    public $airline_data;

    /**
     * @var DLocalProcessingSettings
     */
    public $dlocal;

    /**
     * @var Aggregator
     */
    public $aggregator;


}
