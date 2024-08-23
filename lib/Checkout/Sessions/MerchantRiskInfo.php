<?php

namespace Checkout\Sessions;

use DateTime;

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

    /**
     * @var string value of ReorderItemsIndicatorType
     */
    public $reorder_items_indicator;

    /**
     * @var string value of PreOrderPurchaseIndicatorType
     */
    public $pre_order_purchase_indicator;

    /**
     * @var DateTime
     */
    public $pre_order_date;

    /**
     * @var string
     */
    public $gift_card_amount;

    /**
     * @var string
     */
    public $gift_card_currency;

    /**
     * @var string
     */
    public $gift_card_count;
}
