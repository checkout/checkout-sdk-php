<?php

namespace Checkout\Sessions;

use DateTime;
use Checkout\Common\CardholderAccountAgeIndicatorType;
use Checkout\Common\AccountChangeIndicatorType;
use Checkout\Common\AccountPasswordChangeIndicatorType;
use Checkout\Common\AccountTypeCardProductType;

final class CardholderAccountInfo
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
     * @deprecated This property will be removed in the future, and should not be used.
     */
    public $authentication_method;

    /**
     * @var string value of CardholderAccountAgeIndicatorType
     */
    public $cardholder_account_age_indicator;

    /**
     * @var DateTime
     */
    public $account_change;

    /**
     * @var string value of AccountChangeIndicatorType
     */
    public $account_change_indicator;

    /**
     * @var DateTime
     */
    public $account_date;

    /**
     * @var string
     */
    public $account_password_change;

    /**
     * @var string value of AccountPasswordChangeIndicatorType
     */
    public $account_password_change_indicator;

    /**
     * @var int
     */
    public $transactions_per_year;

    /**
     * @var DateTime
     */
    public $payment_account_age;

    /**
     * @var DateTime
     */
    public $shipping_address_usage;

    /**
     * @var string value of AccountTypeCardProductType
     */
    public $account_type;

    /**
     * @var string
     */
    public $account_id;

    /**
     * @var ThreeDsRequestorAuthenticationInfo
     */
    public $three_ds_requestor_authentication_info;
}
