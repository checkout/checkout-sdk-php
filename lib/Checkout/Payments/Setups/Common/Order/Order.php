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

    /**
     * The unique identifier for the invoice.
     * [Optional]
     * @var string
     */
    public $invoice_id;

    /**
     * The total shipping amount for the order.
     * [Optional]
     * >= 0
     * @var int
     */
    public $shipping_amount;

    /**
     * The total surcharge amount for the order.
     * [Optional]
     * >= 0
     * @var int
     */
    public $surcharge_amount;

    /**
     * The total tax amount for the order.
     * [Optional]
     * >= 0
     * @var int
     */
    public $tax_amount;

    /**
     * The total tipping amount for the order.
     * [Optional]
     * >= 0
     * @var int
     */
    public $tipping_amount;

    /**
     * The amount allocations representing the sub-entities on whose behalf the payment is processed.
     * [Optional]
     * @var array of PaymentSetupAmountAllocation
     */
    public $amount_allocations;
}
