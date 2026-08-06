<?php

namespace Checkout\Sessions;

use Checkout\Common\Currency;
use Checkout\Sessions\Channel\ChannelData;
use Checkout\Sessions\Source\SessionSource;
use Checkout\Sessions\Completion\CompletionInfo;
use Checkout\Sessions\Source\SessionCardSource;
use Checkout\Sessions\DeviceInformation;
use Checkout\Sessions\GoogleSpa;

final class SessionRequest
{
    /**
     * The source of the authentication.
     * [Required]
     * @var SessionSource
     */
    public $source;

    /**
     * The payment amount in the minor currency unit.
     * For recurring and installment payment types, this value is required and must be greater than
     * zero. Omitting this value will set authentication_category to non_payment.
     * [Optional]
     * min 0
     * max 48 characters
     * @var int
     */
    public $amount;

    /**
     * The three-letter ISO currency code.
     * [Required]
     * min 3 characters
     * max 3 characters
     * @var string value of Currency
     */
    public $currency;

    /**
     * The processing channel to be used for the session. Required if this was not set in the
     * request for the OAuth token.
     * [Optional]
     * ^(pc)_(\w{26})$
     * @var string
     */
    public $processing_channel_id;

    /**
     * Information related to authentication for payfac payments.
     * [Optional]
     * @var SessionMarketplaceData
     */
    public $marketplace;

    /**
     * Indicates the type of payment this session is for. Please note the spelling of installment
     * consists of two l's.
     * [Optional]
     * Default: AuthenticationType::$regular
     * @var string value of AuthenticationType
     */
    public $authentication_type;

    /**
     * Indicates the category of the authentication request.
     * [Optional]
     * Default: Category::$payment
     * @var string value of Category
     */
    public $authentication_category;

    /**
     * Additional information about the cardholder's account.
     * [Optional]
     * @var CardholderAccountInfo
     */
    public $account_info;

    /**
     * Indicates whether a challenge is requested for this session.
     * The exemption values are accepted only by POST /sessions; see
     * SessionChallengeIndicatorType.
     * [Optional]
     * Default: SessionChallengeIndicatorType::$no_preference
     * max 50 characters
     * @var string value of SessionChallengeIndicatorType
     */
    public $challenge_indicator;

    /**
     * An optional dynamic billing descriptor.
     * [Optional]
     * @var SessionsBillingDescriptor
     */
    public $billing_descriptor;

    /**
     * A reference you can later use to identify this payment, such as an order number.
     * Do not pass sensitive information in this field, for example card details.
     * [Optional]
     * max 100 characters
     * @var string
     */
    public $reference;

    /**
     * Additional information about the cardholder's purchase.
     * [Optional]
     * @var MerchantRiskInfo
     */
    public $merchant_risk_info;

    /**
     * Identifies the type of transaction being authenticated.
     * [Optional]
     * Default: TransactionType::$goods_service
     * max 50 characters
     * @var string value of TransactionType
     */
    public $transaction_type;

    /**
     * The shipping address. Any special characters will be replaced.
     * [Optional]
     * @var SessionAddress
     */
    public $shipping_address;

    /**
     * Indicates whether the cardholder shipping address and billing address are the same.
     * [Optional]
     * @var bool
     */
    public $shipping_address_matches_billing;

    /**
     * The redirect information needed for callbacks or redirects after the payment is completed.
     * [Required]
     * @var CompletionInfo
     */
    public $completion;

    /**
     * The information gathered from the environment used to initiate the session.
     * [Optional]
     * @var ChannelData
     */
    public $channel_data;

    /**
     * Details of a recurring authentication. This property is needed only for a recurring
     * authentication type. Value will be ignored in any other cases.
     * [Optional]
     * @var Recurring
     */
    public $recurring;

    /**
     * Details of an installment authentication. This property is needed only for an installment
     * authentication type. Value will be ignored in any other cases.
     * [Optional]
     * @var Installment
     */
    public $installment;

    /**
     * Optionally opt into request optimization.
     * [Optional]
     * @var Optimization
     */
    public $optimization;

    /**
     * Details of a previous transaction.
     * [Optional]
     * @var InitialTransaction
     */
    public $initial_transaction;

    /**
     * Indicates the chosen experience(s) for this session.
     * Available experiences include: 3ds, google_spa
     * [Optional]
     * @var string[]|null $preferred_experiences values of Experience
     */
    public $preferred_experiences;

    /**
     * Google SPA properties (non-hosted only).
     * [Optional]
     * @var GoogleSpa|null $google_spa
     */
    public $google_spa;

    /**
     * Details of the device from which the authentication originated.
     * [Optional]
     * @var DeviceInformation|null $device_information
     */
    public $device_information;

    public function __construct()
    {
        $this->source = new SessionCardSource();
        $this->authentication_type = AuthenticationType::$regular;
        $this->authentication_category = Category::$payment;
        $this->challenge_indicator = SessionChallengeIndicatorType::$no_preference;
        $this->transaction_type = TransactionType::$goods_service;
    }
}
