<?php

namespace Checkout\Payments\Setups\Common\Customer;

use Checkout\Common\EmptyAsObject;
use DateTime;
use Checkout\Common\DateOnly;

/**
 * Details of the account the customer holds with the merchant (PaymentSetupMerchantAccount).
 */
#[EmptyAsObject]
class MerchantAccount
{
    /**
     * The merchant's unique identifier for the customer's account.
     * [Optional]
     * @var string
     */
    public $id;

    /**
     * The date the customer registered their account with the merchant.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $registration_date;

    /**
     * The date the customer's account with the merchant was last modified.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $last_modified;

    /**
     * Specifies if the customer is a returning customer.
     * [Optional]
     * @var bool
     */
    public $returning_customer;

    /**
     * The date of the customer's first transaction.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $first_transaction_date;

    /**
     * The date of the customer's most recent transaction.
     * [Optional]
     * Format: yyyy-MM-dd
     * @var DateTime
     */
    #[DateOnly]
    public $last_transaction_date;

    /**
     * The total number of orders made by the customer.
     * [Optional]
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
