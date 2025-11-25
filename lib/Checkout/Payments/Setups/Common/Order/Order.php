<?php

namespace Checkout\Payments\Setups\Common\Order;

use Checkout\Payments\ShippingDetails;

class Order
{
    /**
     * @var array of Product
     */
    public $items;

    /**
     * @var ShippingDetails
     */
    public $shipping;

    /**
     * @var array of OrderSubMerchant
     */
    public $sub_merchants;

    /**
     * @var int
     */
    public $discount_amount;
}
