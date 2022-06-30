<?php

namespace Checkout\Sessions;

class MerchantRiskInfo
{
    /**
     * @var string
     */
    public $delivery_email;

    /**
     * @var string value of DeliveryTimeframe
     */
    public $delivery_timeframe;

    /**
     * @var bool
     */
    public $is_preorder;

    /**
     * @var bool
     */
    public $is_reorder;

    /**
     * @var string value of ShippingIndicator
     */
    public $shipping_indicator;
}
