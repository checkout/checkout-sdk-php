<?php

namespace Checkout\Payments;

use DateTime;

class ThreeDsRequest
{
    /**
     * @var bool
     */
    public $enabled = true;

    /**
     * @var bool
     */
    public $attempt_n3d;

    /**
     * @var string
     */
    public $eci;

    /**
     * @var string
     */
    public $cryptogram;

    /**
     * @var string
     */
    public $xid;

    /**
     * @var string
     */
    public $version;

    /**
     * @var string value of Exemption
     */
    public $exemption;

    /**
     * @var string value of ChallengeIndicatorType
     */
    public $challenge_indicator;

    /**
     * @var bool
     */
    public $allow_upgrade;

    //Not available on previous

    /**
     * @var string
     */
    public $status;

    /**
     * @var datetime
     */
    public $authentication_date;

    /**
     * @var int
     */
    public $authentication_amount;

    /**
     * @var string
     */
    public $flow_type;

    /**
     * @var string
     */
    public $status_reason_code;

    /**
     * @var string
     */
    public $challenge_cancel_reason;

    /**
     * @var string
     */
    public $score;

    /**
     * @var string
     */
    public $cryptogram_algorithm;

    /**
     * @var string
     */
    public $authentication_id;

    /**
     * The information about how the 3DS Requestor authenticated the cardholder
     * before or during the transaction.
     * [Optional]
     * @var MerchantAuthenticationInfo|null $merchant_authentication_info
     */
    public $merchant_authentication_info;

    /**
     * Additional information about the cardholder's account.
     * [Optional]
     * @var AccountInfo|null $account_info
     */
    public $account_info;

    /**
     * The details of a previous 3DS authenticated transaction.
     * Required when 3DS authentication was performed by a third-party provider.
     * [Optional]
     * @var InitialAuthentication|null $initial_authentication
     */
    public $initial_authentication;
}
