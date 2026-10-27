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
     * The payment amount of the customer's most recent transaction. The specification types this
     * as a number (example 55.99); the API currently stores it as an integer.
     * [Optional]
     * @var float
     */
    public $last_payment_amount;
}
