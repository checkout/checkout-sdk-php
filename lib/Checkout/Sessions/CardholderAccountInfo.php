<?php

namespace Checkout\Sessions;

class CardholderAccountInfo
{
    /**
     * @var int
     */
    public $purchase_count;

    /**
     * @var string
     */
    public $account_age;

    /**
     * @var int
     */
    public $add_card_attempts;

    /**
     * @var string
     */
    public $shipping_address_age;

    /**
     * @var bool
     */
    public $account_name_matches_shipping_name;

    /**
     * @var bool
     */
    public $suspicious_account_activity;

    /**
     * @var int
     */
    public $transactions_today;

    /**
     * @var string value of AuthenticationMethod
     */
    public $authentication_method;
}
