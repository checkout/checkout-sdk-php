<?php

namespace Checkout\Sessions;

class MerchantRiskInfo
{
    /**
     * @var string
     */
    public $delivery_email;

    /**
     * @var DeliveryTimeframe
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
     * @var ShippingIndicator
     */
    public $shipping_indicator;
}
