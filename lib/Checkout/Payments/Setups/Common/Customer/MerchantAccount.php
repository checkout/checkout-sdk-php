<?php

namespace Checkout\Payments\Setups\Common\Customer;

use DateTime;

class MerchantAccount
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var DateTime
     */
    public $registration_date;

    /**
     * @var DateTime
     */
    public $last_modified;

    /**
     * @var bool
     */
    public $returning_customer;

    /**
     * @var DateTime
     */
    public $first_transaction_date;

    /**
     * @var DateTime
     */
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
