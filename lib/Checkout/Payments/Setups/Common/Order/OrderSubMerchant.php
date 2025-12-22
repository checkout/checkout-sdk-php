<?php

namespace Checkout\Payments\Setups\Common\Order;

use DateTime;

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
     * @var DateTime
     */
    public $registration_date;
}
