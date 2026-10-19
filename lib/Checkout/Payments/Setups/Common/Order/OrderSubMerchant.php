<?php

namespace Checkout\Payments\Setups\Common\Order;

use DateTime;
use Checkout\Common\DateOnly;

/**
 * Details about a sub-merchant in the order.
 */
class OrderSubMerchant
{
    /**
     * The unique identifier for the sub-merchant.
     * [Optional]
     * @var string
     */
    public $id;

    /**
     * The product category for the sub-merchant.
     * [Optional]
     * @var string
     */
    public $product_category;

    /**
     * Deprecated. Use $number_of_sales instead.
     *
     * Not in the specification: the API does not read number_of_trades, so the value is ignored. Set
     * $number_of_sales instead. Retained for backwards compatibility and will be removed in the next
     * major version.
     * [Optional]
     * @var int
     * @deprecated Use $number_of_sales instead. Will be removed in the next major version.
     */
    public $number_of_trades;

    /**
     * The number of orders the sub-merchant has processed.
     * [Optional]
     * @var int
     */
    public $number_of_sales;

    /**
     * The date the sub-merchant was registered.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $registration_date;
}
