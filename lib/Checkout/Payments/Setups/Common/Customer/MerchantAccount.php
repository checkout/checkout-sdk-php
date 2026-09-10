<?php

namespace Checkout\Payments\Setups\Common\Customer;

use DateTime;
use Checkout\Common\DateOnly;

class MerchantAccount
{
    /**
     * @var string
     */
    public $id;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $registration_date;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $last_modified;

    /**
     * @var bool
     */
    public $returning_customer;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $first_transaction_date;

    /**
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $last_transaction_date;

    /**
     * @var int
     */
    public $total_order_count;

    /**
     * @var int
     */
    public $last_payment_amount;
}
