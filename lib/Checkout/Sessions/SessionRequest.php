<?php

namespace Checkout\Sessions;

use Checkout\Common\ChallengeIndicatorType;
use Checkout\Common\Currency;
use Checkout\Sessions\Channel\ChannelData;
use Checkout\Sessions\Completion\CompletionInfo;
use Checkout\Sessions\Source\SessionSource;

class SessionRequest
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
     * @var Currency
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
     * @var AuthenticationType
     */
    public $authentication_type;

    /**
     * @var Category
     */
    public $authentication_category;

    /**
     * @var ChallengeIndicatorType
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
     * @var TransactionType
     */
    public $transaction_type;

    /**
     * @var SessionAddress
     */
    public $shipping_address;

    /**
     * @var CompletionInfo
     */
    public $completion;

    /**
     * @var ChannelData
     */
    public $channel_data;
}
