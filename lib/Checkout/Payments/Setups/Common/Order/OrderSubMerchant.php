<?php

namespace Checkout\Payments\Setups\Common\Order;

use DateTime;
use Checkout\Common\DateOnly;

class OrderSubMerchant
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $product_category;

    /**
     * @var int
     */
    public $number_of_trades;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $registration_date;
}
