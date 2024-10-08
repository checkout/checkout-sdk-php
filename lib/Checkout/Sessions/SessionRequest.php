<?php

namespace Checkout\Sessions;

use Checkout\Common\Currency;
use Checkout\Common\ChallengeIndicatorType;
use Checkout\Sessions\Channel\ChannelData;
use Checkout\Sessions\Source\SessionSource;
use Checkout\Sessions\Completion\CompletionInfo;
use Checkout\Sessions\Source\SessionCardSource;

final class SessionRequest
{
    /**
     * @var SessionSource
     */
    public $source;

    /**
     * @var int
     */
    public $amount;

    /**
     * @var string value of Currency
     */
    public $currency;

    /**
     * @var string
     */
    public $processing_channel_id;

    /**
     * @var SessionMarketplaceData
     */
    public $marketplace;

    /**
     * @var string value of AuthenticationType
     */
    public $authentication_type;

    /**
     * @var string value of Category
     */
    public $authentication_category;

    /**
     * @var CardholderAccountInfo
     */
    public $account_info;

    /**
     * @var string value of ChallengeIndicatorType
     */
    public $challenge_indicator;

    /**
     * @var SessionsBillingDescriptor
     */
    public $billing_descriptor;

    /**
     * @var string
     */
    public $reference;

    /**
     * @var MerchantRiskInfo
     */
    public $merchant_risk_info;

    /**
     * @var string
     */
    public $prior_transaction_reference;

    /**
     * @var string value of TransactionType
     */
    public $transaction_type;

    /**
     * @var SessionAddress
     */
    public $shipping_address;

    /**
     * @var bool
     */
    public $shipping_address_matches_billing;

    /**
     * @var CompletionInfo
     */
    public $completion;

    /**
     * @var ChannelData
     */
    public $channel_data;

    /**
     * @var Recurring
     */
    public $recurring;

    /**
     * @var Installment
     */
    public $installment;

    /**
     * @var Optimization
     */
    public $optimization;

    /**
     * @var InitialTransaction
     */
    public $initial_transaction;

    public function __construct()
    {
        $this->source = new SessionCardSource();
        $this->authentication_type = AuthenticationType::$regular;
        $this->authentication_category = Category::$payment;
        $this->challenge_indicator = ChallengeIndicatorType::$no_preference;
        $this->transaction_type = TransactionType::$goods_service;
    }
}
