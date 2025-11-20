<?php

namespace Checkout\Payments;

use DateTime;

class CustomerSummary
{
    /**
     * The date when the customer registered with the platform.
     * @var DateTime
     */
    public $registration_date;

    /**
     * The date of the customer's first transaction.
     * @var DateTime
     */
    public $first_transaction_date;

    /**
     * The date of the customer's last payment.
     * @var DateTime
     */
    public $last_payment_date;

    /**
     * The total number of orders placed by the customer.
     * @var int
     */
    public $total_order_count;

    /**
     * The amount of the customer's last payment.
     * @var float
     */
    public $last_payment_amount;

    /**
     * Whether the customer is classified as a premium customer.
     * @var bool
     */
    public $is_premium_customer;

    /**
     * Whether the customer is a returning customer.
     * @var bool
     */
    public $is_returning_customer;

    /**
     * The lifetime value of the customer.
     * @var float
     */
    public $lifetime_value;
}
