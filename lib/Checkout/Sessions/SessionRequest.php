<?php

namespace Checkout\Sessions;

class SessionRequest
{
    // SessionSource
    public $source;

    public $amount;

    public $currency;

    public $processing_channel_id;

    // SessionMarketplaceData
    public $marketplace;

    public $authentication_type;

    public $authentication_category;

    public $challenge_indicator;

    // SessionsBillingDescriptor
    public $billing_descriptor;

    public $reference;

    public $transaction_type;

    // SessionAddress
    public $shipping_address;

    // CompletionInfo
    public $completion;

    // ChannelData
    public $channel_data;

}
